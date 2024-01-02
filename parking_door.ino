//وارد کردن کتابخانه ها
#include <EEPROM.h> // کتابخانه حافظه eeprom
#include <ESP8266WiFi.h> // کتابخانه esp8266
#include <MFRC522.h> // کتابخانه ماژول ار اف ای دی
#include <SPI.h>
#include <ESP8266HTTPClient.h> // کتابخانه راه اندازی درخواست http
#include <WiFiClient.h>

// تعریف پایه های رله و ال ای دی و پایه های ماژول ار اف ای دی
#define RST_PIN D3
#define SS_PIN  D8
#define RELAY_1_PIN D1
#define RELAY_2_PIN D2
#define LED_GREEN_PIN D4
#define LED_RED_PIN D0

// رمز و پسورد وای فای مورد نظر
const char* ssid     = "NTW";         // The SSID (name) of the Wi-Fi network you want to connect to
const char* password = "0303301306004";     // The password of the Wi-Fi network

// کارت خوانده شده
byte presentedCard[4];
boolean state = false;

// آدرس سرور
const char* serverAddress = "http://www.payner.ir:80/Project/process.php"; //Your Domain name with URL path or IP address with path

unsigned long lastTime = 0;
// Set timer to 5 seconds (5000)
unsigned long timerDelay = 8000;

// مقدار دهی اولیه ماژول ار اف ای دی
MFRC522 rf(SS_PIN, RST_PIN);

void setup() {
  // راه اندازی سریال در بادریت مورد نظر
  Serial.begin(115200);
  delay(100);
  EEPROM.begin(512);
  delay(100);
  
  // تعیین ورودی یا خروجی بودن پین ها
  pinMode(RELAY_1_PIN, OUTPUT);
  pinMode(RELAY_2_PIN, OUTPUT);
  pinMode(LED_GREEN_PIN, OUTPUT);
  pinMode(LED_RED_PIN, OUTPUT);

  // وضعیت اولیه پین
  digitalWrite(RELAY_1_PIN, HIGH);
  digitalWrite(RELAY_2_PIN, HIGH);
  digitalWrite(LED_RED_PIN, LOW);

  // راه اندازی esp8266 در مود station
  WiFi.mode(WIFI_STA);
  // اتصال به وای فای مورد نظر
  WiFi.begin(ssid, password);

  // 10 ثانیه چشمک زدن چراغ قرمز به منظور اتصال به وای فای
  int count = 0;
  while(count < 10){
    digitalWrite(LED_RED_PIN, HIGH);
    delay(500);
    digitalWrite(LED_RED_PIN, LOW);
    delay(500);
    count++;
  }

  // شروع کار ماژول rfid
  SPI.begin();
  rf.PCD_Init();
}

void loop() {
  // شرط برقراره در صورتی که کارتی به ماژول نزدیک نشه - اگه کارتی تشخیص داده بشه وارد شرط نمیشه
  if ( ! rf.PICC_IsNewCardPresent() || ! rf.PICC_ReadCardSerial() ) {
    if ((millis() - lastTime) > timerDelay) { // اگه از تایمی کی بالا تعریف کردیم گذشته بود وارد شرط میشه
      digitalWrite(LED_GREEN_PIN, LOW);
      if(WiFi.status() == WL_CONNECTED) { // برسی اتصال به وای فای
        digitalWrite(LED_RED_PIN, HIGH);
        String response = httpGETRequest(serverAddress); // ایحاد درخواست
        Serial.print("Server -> ");
        Serial.println(serverAddress);
        Serial.print("Response -> ");
        Serial.println(response);
        if(response != "{}") {
          if(response == "off"){
            state = false;
          } else if (response == "on"){
            state = true;
          }
          control();
        }
      } else {
        digitalWrite(LED_RED_PIN, LOW);
      }

     lastTime = millis(); 
    }
    
    digitalWrite(LED_GREEN_PIN, HIGH);
    delay(50);
    return;
  }
  
  for (byte i = 0; i < rf.uid.size; i++) { // خواندن کارت
    presentedCard[i] = rf.uid.uidByte[i];
  }

  if (checkID(presentedCard)) { // بررسی وجود کارت در حافظه - اگه بود رله رو کنترل کن
    control();
  } else { // اگه نبود بیا برو تو حالت افزودن کارت
    int count = 0;
    while(! rf.PICC_IsNewCardPresent() || ! rf.PICC_ReadCardSerial()) { // تا 5 ثانیه اگه کارت قدیمی نزد کارت جدیدو اضافه نکن
      digitalWrite(LED_RED_PIN, LOW);
      digitalWrite(LED_GREEN_PIN, LOW);
      delay(250);
      digitalWrite(LED_RED_PIN, HIGH);
      digitalWrite(LED_GREEN_PIN, HIGH);
      delay(250);
      if (count >= 10) {
        return;
      }
      count++;
    }
    
    byte userCard[4];
    for (byte i = 0; i < rf.uid.size; i++) {
      userCard[i] = rf.uid.uidByte[i];
    }

    if(checkID(userCard)){
      addNewUsers(presentedCard);
    }
  }
  delay(400);
}

// تابع کنترل رله ها
void control() {
  if(!state) {
    digitalWrite(RELAY_1_PIN, HIGH);
    digitalWrite(RELAY_2_PIN, HIGH);
    state = true;
  } else {
    digitalWrite(RELAY_1_PIN, LOW);
    digitalWrite(RELAY_2_PIN, LOW);
    state = false;
  }  
}

// تابع برسی آی دی کارت خوانده شده در حافظه
boolean checkID( byte card[] ) {
  byte users = EEPROM.read(0);
  byte storedCard[4];

  if(users <= 0){
    addNewUsers(card);
    return true;
  }
  
  for ( byte user = 0 ; user < users ; user++) {
    for ( byte i = 0 ; i < 4 ; i++) {
        storedCard[i] = EEPROM.read( user * 4 + 1 + i );
    }
    boolean match = true;
    for ( byte i = 0; i < 4; i++ ) {
      if ( card[i] != storedCard[i]) {
        match = false;
        break;
      }
    }
    if(match){
      return true;
    }
  }
  
  return false;
}

// تابع افزودن کاربر جدید در حافظه فلش
void addNewUsers(byte newUser[]) {
  byte users = EEPROM.read(0);
  users += 1;
  EEPROM.write(0, users);
  for ( byte i = 0 ; i < 4 ; i++ ) {
    EEPROM.write( (users - 1) * 4 + 1 + i , newUser[i] ); 
  }
  
  if (!EEPROM.commit()) {
    Serial.println("ERROR! EEPROM commit failed");
  }
}

// تابع درخواست http به صورت get
String httpGETRequest(const char* serverName) {
  WiFiClient client;
  HTTPClient http;
  // تایم اوت
  http.setTimeout(3000);
  http.begin(client, serverName);
  
  // Send HTTP GET request
  int httpResponseCode = http.GET();
  String payload = "{}";

  if (httpResponseCode == 200) { // اگر کد 200 بود اطلاعات صفحه رو بخون
    payload = http.getString();
  }
  
  http.end();
  return payload;
}
