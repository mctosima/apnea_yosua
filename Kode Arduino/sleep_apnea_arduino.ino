#include  "MAX30100_PulseOximeter.h"
#include  <SoftwareSerial.h>
#include <virtuabotixRTC.h>
#include <SPI.h>
#include <SD.h>

#define Reporting_Period_ms 1000 //data akan diupdate setiap 1 detik 
String gettime();

PulseOximeter pox;
String datas;
unsigned long LastReport = 0;
float vin;
float vin_sum = 0;
int count = 0;
int i = 0;
   
const int x = A0;
const int y = A1;
const int z = A2;
const int breath = A3;
const int runnings = 40;
const int failure = 41;
virtuabotixRTC myrtc(7, 8, 9);


void setup() {
  Serial.begin(9600);

  pinMode(breath, INPUT);
  pinMode(x, INPUT);
  pinMode(y, INPUT);
  pinMode(z, INPUT);
  pinMode(runnings, OUTPUT);
  pinMode(failure, OUTPUT);
  digitalWrite(runnings, LOW);
  digitalWrite(failure, LOW);
  if (SD.begin(53))
    {
    Serial.println("SD card is present & ready");
    } 
    else
    {
    digitalWrite(failure, HIGH);
    Serial.println("SD card missing or failure");
    while(1);  //wait here forever
    }
    
  if (!pox.begin()) {
        digitalWrite(failure, HIGH);
        Serial.println("FAILED");
        for(;;);
    } else {
        Serial.println("SUCCESS");
    }  
  //myrtc.setDS1302Time(0, 46, 11, 7, 6, 3, 2021);
  pox.setIRLedCurrent(MAX30100_LED_CURR_7_6MA);
  pox.update();
  while(pox.getSpO2() == 0){
    pox.update();
   Serial.println(pox.getSpO2());
    //berjalan sampai spo2 mendapatkan nilai != 0
  }
  digitalWrite(runnings, HIGH);
}

void loop() {
  unsigned long currentMillis = millis();
  pox.update(); //update nilai dari oximeter
  vin = analogRead(breath); // membaca nilai dari sensor suhu
  
  vin = ((vin * 4870.0) / 1024);
  vin_sum = vin_sum + vin;
  count++;

  if (currentMillis - LastReport >= Reporting_Period_ms){
     datas = gettime() + ",";
     datas = datas +String((int)pox.getHeartRate())+",";
     datas = datas + String(pox.getSpO2())+",";

     vin_sum = vin_sum/count;
     datas = datas + String(vin_sum)+",";
     count = 0;

    int xvalue = analogRead(x);  //read from xpin
    int xmap = map(xvalue, 276, 400, -100, 100);
    datas = datas + String((float)xmap/(-100.00))+",";
    int yvalue = analogRead(y);  //read from ypin
    int ymap = map(yvalue, 276, 400, -100, 100);
    datas = datas + String((float)ymap/(-100.00))+",";
    int zvalue = analogRead(z);  //read from zpin
    int zmap = map(zvalue, 276, 400, -100, 100);
    datas = datas + String((float)zmap/(-100.00));
    Serial.print((float)xmap/(-100.00));
    Serial.print(" ");
    Serial.print((float)ymap/(-100.00));
    Serial.print(" ");
    Serial.println((float)zmap/(-100.00));
    //Serial.println(datas);
    File dataFile = SD.open("data.csv", FILE_WRITE);

  // if the file is available, write to it:
    if (dataFile) {
      dataFile.println(datas);
      dataFile.close();
       datas = "";
    
    }
    LastReport = millis();
  }
}

String gettime(){
  myrtc.updateTime();
  String day,month,year,hour,minute,second;
  if(myrtc.hours<10){
    hour = "0"+String(myrtc.hours);
  }else{
    hour = String(myrtc.hours);
  }
  if(myrtc.minutes<10){
    minute = "0"+String(myrtc.minutes);
  }else{
    minute = String(myrtc.minutes);
  }
  if(myrtc.seconds<10){
    second = "0"+String(myrtc.seconds);
  }else{
    second = String(myrtc.seconds);
  }
  return String(myrtc.dayofmonth)+"-"+String(myrtc.month)+"-"+String(myrtc.year)+" "+
                 hour+":"+minute+":"+second;
}
