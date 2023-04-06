[code]
#include <Arduino.h>
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecureBearSSL.h>
#include <Servo.h>


ESP8266WiFiMulti WiFiMulti;

Servo myservo;  // create servo object to control a servo
int pirPin = 2;    // PIR sensor connected to digital pin 2
int servoPin = 13;   // Servo connected to digital pin 13
int pirState = HIGH;   // start assuming no motion detected
int val = 0;   // variable to store the PIR sensor output value
int servoPos = 0; // variable to store servo position

void setup() {
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);
  WiFiMulti.addAP("BW_Jacket");  // when on BW network change to ("BW_Jacket")
  
  myservo.attach(servoPin);  // attaches the servo on pin 13 to the servo object
  pinMode(pirPin, INPUT); // initialize PIR sensor as an input
  myservo.write(servoPos); // set servo to initial position
}

void loop() {
  val = digitalRead(pirPin); // read PIR sensor output value
  
  if (val == LOW) { // if motion detected
    myservo.write(180); // set servo to 180 degrees
    if ((WiFiMulti.run() == WL_CONNECTED)) {
      WiFiClient client;
      HTTPClient https;
      Serial.print("[HTTPS] begin...\n");
      if (https.begin(client, "http://10.19.13.178/sensorReadings.php")) {
        DynamicJsonDocument doc(1024);
        doc["sensorId"] = "csc263";
        doc["temp"] = 72.3f;
        doc["humidity"] = 0.44f;
        char serialized[1024];
        serializeJson(doc, serialized);
        Serial.printf("Here is our serialized version %s", serialized);
        Serial.print("[HTTPS] POST...\n");
        int httpCode = https.POST(serialized);
        if (httpCode > 0) {
          Serial.printf("[HTTPS] POST... code: %d\n", httpCode);
          if (httpCode == HTTP_CODE_OK || httpCode == HTTP_CODE_MOVED_PERMANENTLY) {
            String payload = https.getString();
            DynamicJsonDocument responseDoc(1024);
            deserializeJson(responseDoc, payload);
            const char* status = responseDoc["status"];
            Serial.printf("Status is %s\n", status);
            }
        } else {
          Serial.printf("[HTTPS] POST... failed, error: %s\n", https.errorToString(httpCode).c_str());
         }
          https.end();
        } else {
        Serial.printf("[HTTPS] Unable to connect\n");
      }
    }
    Serial.println("Wait 10s before next round...");
    delay(1000); // wait for 1 second
    myservo.write(0); // set servo to 0 degrees
    servoPos = 0; // update servo position
    pirState = LOW; // update PIR state to LOW (motion  not detected)
    delay(9000);
  }
}
[/code]