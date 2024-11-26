#include <WiFi.h>
#include "time.h"
#include "sntp.h"
#include <WiFiClient.h>
#include <WebServer.h>
#include <Stepper.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

const char* ssid = "t";
const char* password = "fikri280012";

const char* ntpServer1 = "my.pool.ntp.org";
const char* ntpServer2 = "asia.pool.ntp.org";
const long gmtOffset_sec = 28800;
const int daylightOffset_sec = 0;

const int lcdColumns = 16;
const int lcdRows = 2;
LiquidCrystal_I2C lcd(0x27, lcdColumns, lcdRows);

bool medicineMessageDisplayed = false;
unsigned long stepperRotateTime = 0;
const unsigned long buttonTimeout = 60000;  // 1 minute timeout
bool timeoutWarningShown = false;
bool stepperRotated = false;

void printLocalTime() {
  if (medicineMessageDisplayed) {
    return;  // Do not update time if medicine message is displayed
  }

  struct tm timeinfo;
  if (!getLocalTime(&timeinfo)) {
    Serial.println("No time available (yet)");
    return;
  }

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Date: ");
  lcd.print(timeinfo.tm_year + 1900);
  lcd.print("-");
  if (timeinfo.tm_mon < 9) {
    lcd.print("0");
  }
  lcd.print(timeinfo.tm_mon + 1);
  lcd.print("-");
  if (timeinfo.tm_mday < 10) {
    lcd.print("0");
  }
  lcd.print(timeinfo.tm_mday);
  lcd.setCursor(0, 1);
  lcd.print("Time: ");
  if (timeinfo.tm_hour < 10) {
    lcd.print("0");
  }
  lcd.print(timeinfo.tm_hour);
  lcd.print(":");
  if (timeinfo.tm_min < 10) {
    lcd.print("0");
  }
  lcd.print(timeinfo.tm_min);
  lcd.print(":");
  if (timeinfo.tm_sec < 10) {
    lcd.print("0");
  }
  lcd.print(timeinfo.tm_sec);
}

void timeavailable(struct timeval* t) {
  Serial.println("Got time adjustment from NTP!");
  printLocalTime();
}

#define IN1 14
#define IN2 27
#define IN3 26
#define IN4 25
const int stepsPerRevolution = 2048;

const int ledPin = 19;
const int buzzerPin = 18;
const int buttonPin = 4;

WebServer server(80);  // Create a web server listening on port 80
Stepper myStepper(stepsPerRevolution, IN1, IN3, IN2, IN4);

int currentState;   // the current reading from the input pin
int previousState;  // the previous reading from the input pin

void setup() {
  Serial.begin(115200);
  pinMode(ledPin, OUTPUT);
  pinMode(buzzerPin, OUTPUT);
  pinMode(buttonPin, INPUT_PULLUP);
  lcd.init();
  lcd.backlight();
  sntp_set_time_sync_notification_cb(timeavailable);
  configTime(gmtOffset_sec, daylightOffset_sec, ntpServer1, ntpServer2);

  Serial.printf("Connecting to %s ", ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println(" CONNECTED");

  myStepper.setSpeed(5);

  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  // Define server routes
  server.enableCORS();
  server.enableCrossOrigin();
  server.on("/index.html", HTTP_GET, handleRequest);

  server.begin();
  Serial.println("HTTP server started.");

  previousState = digitalRead(buttonPin);
}

void loop() {
  server.handleClient();

  int buttonState = digitalRead(buttonPin);

  // Check for button press to reset LCD
  if (buttonState == LOW && previousState == HIGH) {
    digitalWrite(ledPin, LOW);
    digitalWrite(buzzerPin, LOW);
    sendButtonRequest();
    if (medicineMessageDisplayed) {
      // Reset states after button press
      medicineMessageDisplayed = false;
      timeoutWarningShown = false;  // Reset timeout warning
      stepperRotated = false;       // Reset stepper rotated flag
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("THANK YOU");
      delay(5000);       // Display "THANK YOU" for 5 seconds
      medicineMessageDisplayed = false; // Ensure the message is marked off
    }
    printLocalTime();  // Return to displaying the current time
  }

  previousState = buttonState;

  if (!medicineMessageDisplayed) {
    delay(1000);
    printLocalTime();
  }
}

void handleRequest() {
  server.send(200, "text/plain", "Executed");
  rotateStepperAndShowMessage();
}

void rotateStepperAndShowMessage() {
  // Move the stepper motor 90 degrees
  int stepsToRotate = (stepsPerRevolution * 90) / 360;
  myStepper.step(stepsToRotate);

  // Turn on LED and buzzer for 10 seconds
  digitalWrite(ledPin, HIGH);
  digitalWrite(buzzerPin, HIGH);

  // Display medicine message on LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Please take your");
  lcd.setCursor(0, 1);
  lcd.print("medicine");
  medicineMessageDisplayed = true;

  // Update timing for timeout warning
  stepperRotateTime = millis();
  stepperRotated = true;  // Set flag indicating stepper has rotated
}

void sendButtonRequest() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    // Your target URL for the GET request
    http.begin("https://amirulasri.com/fikriainfyp/submitbutton.php");

    int httpCode = http.GET();

    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println(httpCode);
      Serial.println(payload);
    } else {
      Serial.println("Error on HTTP request");
    }

    http.end();
  }
}
