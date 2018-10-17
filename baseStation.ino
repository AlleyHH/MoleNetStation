#include <Arduino.h>
#include "SIM800.h"
#include <RFM69.h>
#include <SPI.h>
#include <SPIFlash.h>
#include <DHT.h>;
#include "Time.h" 
#include "Adafruit_ADS1015.h"
#include "Wire.h"
#include "SDL_Weather_80422.h"
#include <LowPower.h>

#define NODEID 69 //unique for each node on same network
#define FREQUENCY RF69_433MHZ
#define ENCRYPTKEY "sampleEncryptKey" //encryption key
#define NETWORKID 100 //should be same on all nodes in the network
#define GATEWAYID 65 //ID of Gateway
#define RADIOPOWERLEVEL 31

#define DHTPIN 7    
#define DHTTYPE DHT22  
DHT dht(DHTPIN, DHTTYPE);
#define pinLED     13   
#define pinAnem    2
#define pinRain    0   
#define intAnem    1
#define intRain    2

#define APN "internet"
//static const char* url = "arduino.cc/asciilogo.txt";

#define SERIAL_BAUD 9600 //defining baud rate

RFM69 radio; //declaring radio object
bool promiscuousMode = true; //sniffing all packets on the same network

SDL_Weather_80422 weatherStation(pinAnem, pinRain, intAnem, intRain, A0, SDL_MODE_INTERNAL_AD);

float w_h;  //Stores humidity value
float w_t; //Stores temperature value
uint8_t i;
float w_ws;
float w_wg;
float w_r;
float w_d;

int LED=6;

unsigned long time1;
unsigned long time2;
unsigned long time3;
unsigned long time4;
unsigned long time5;
unsigned long time6;
unsigned long time7;
unsigned long time8;
unsigned long time9;
unsigned long time10;
unsigned long diff1;
unsigned long diff2;
unsigned long diff3;
unsigned long diff4;
unsigned long diff5;

CGPRS_SIM800 gprs;
static const char* data = "params=Ali~likes\n";
char* data2 = "params=Ali~likes\n";

typedef struct 
{
  byte sink_ID;
  byte pos_PACKET_TYPE;
  byte pos_PACKET_LENGTH;
  byte pos_SOURCE_ID;
  byte pos_EPOCH_0;
  byte pos_EPOCH_1;
  byte pos_EPOCH_2;
  byte pos_EPOCH_3;
  byte pos_DIELECTRIC_0;
  byte pos_DIELECTRIC_1;
  byte pos_TEMP_0;
  byte pos_TEMP_1;
  byte pos_PACKETS_SENT_0;
  byte pos_PACKETS_SENT_1;
  byte pos_SENDING_RETRIES;
  byte pos_PACKETS_LOST_0;
  byte pos_PACKETS_LOST_1;
  byte pos_RTT_0;
  byte pos_RTT_1;
  byte pos_RSSI_0;
  byte pos_RSSI_1;
  byte pos_EEPROM_CURRENT_PAGE_0;
  byte pos_EEPROM_CURRENT_PAGE_1;
  byte pos_EEPROM_CURRENT_PAGE_2;
  byte nextHop_ID;

} Packet;

Packet node;

void setup()
{
  Serial.begin(9600);
  while (!Serial);
  Serial.println("Starting Radio");
  radio.initialize(FREQUENCY,NODEID,NETWORKID);
  radio.encrypt(ENCRYPTKEY);
  radio.promiscuous(promiscuousMode);
  
  dht.begin();
  weatherStation.setWindMode(SDL_MODE_SAMPLE, 5.0);
  //weatherStation.setWindMode(SDL_MODE_DELAY, 5.0);
  w_r = 0.0;
  time9 = millis();
  
  pinMode (LED, OUTPUT);

  for (;;) 
  {
    Serial.print("Resetting GSM Shield...");
    while (!gprs.init()) 
    {
      Serial.write('.');
    }
    Serial.println("OK");
    
    Serial.print("Setting up network...");
    byte ret = gprs.setup(APN);
    if (ret == 0)
    {
      break;
    }
    Serial.print("Error code:");
    Serial.println(ret);
    Serial.println(gprs.buffer);
  } 
  delay(3000);
  if (gprs.getOperatorName()) 
  {
    Serial.print("Operator:");
    Serial.println(gprs.buffer);
  }
  int ret = gprs.getSignalQuality();
  if (ret) 
  {
     Serial.print("Signal:");
     Serial.print(ret);
     Serial.println("dB");
  }
}

void loop() 
{
  /*node.sink_ID = random(1,20);
  node.pos_PACKET_TYPE = random(1,20);
  node.pos_PACKET_LENGTH = random(1,20);
  node.pos_SOURCE_ID = random(1,20);
  node.pos_TEMP_0 = random(1,2);
  node.pos_TEMP_1 = random(1,2);
  node.pos_DIELECTRIC_0 = random(1,2);
  node.pos_DIELECTRIC_1 = random(1,2);
  node.pos_RTT_0 = random(1,9);
  node.pos_RTT_1 = random(1,9);
  node.pos_RSSI_0 = random(-1,-9);
  node.pos_RSSI_1 = random(-1,-9);
  node.nextHop_ID = random(1,20);*/
  w_h = dht.readHumidity();
  w_t = dht.readTemperature();
  w_ws = weatherStation.current_wind_speed();
  w_wg = weatherStation.get_wind_gust();
  w_r = w_r + weatherStation.get_current_rain_total()/25.4;
  w_d = weatherStation.current_wind_direction();
  //w_h = random(50,80);
  //w_t= random(-5,25);
  //w_ws= random(4,12);
  //w_wg= random(12,18);
  //w_r= random(1,5);
  //w_d = random(0,360);
  String h = String((int) w_h); 
  String t = String((int) w_t); 
  String ws = String((int) w_ws); 
  String wg = String((int) w_wg); 
  String r = String((int) w_r);
  String d = String((int) w_d);
  String weatherData = "params=" + t + "," +  h + "," + ws + "," + wg + "," + r + "," + d;
  const char * data4 = weatherData.c_str();
  time10 = millis();
  diff5 = time10-time9;
  //Serial.println(diff5);
    if(diff5 >= 120000)
    {
      Serial.println("Posting Weather Station Data");
      time9 = millis();
      postWeather(data4);
    }
  
  if (radio.receiveDone()) //interrupt from RFM69
  {
    if (radio.DATALEN != sizeof(node)) //checking if received packet structure is of the right length
    {
        String kuchNai = "Invalid Data received";
        Serial.print(kuchNai);
    }
    else
    {
      node = *(Packet*)radio.DATA; //accessing received data
      uint16_t temperature =  (uint16_t)node.pos_TEMP_0<<8 | (uint16_t)node.pos_TEMP_1;
      uint16_t dielectric =  (uint16_t)node.pos_DIELECTRIC_0<<8 | (uint16_t)node.pos_DIELECTRIC_1;
      //uint16_t rtt =  (uint16_t)node.pos_RTT_0<<8 | (uint16_t)node.pos_RTT_1;
      uint16_t rtt = 10;
      int16_t rssi =  (uint16_t)radio.RSSI<<8 | (uint16_t)radio.RSSI;
      String sinkID = (String) node.sink_ID;
      String packetType = (String) node.pos_PACKET_TYPE;
      String packetLength = (String) node.pos_PACKET_LENGTH;
      String sourceID = (String) node.pos_SOURCE_ID;
      String tempRaw = (String) temperature;
      int tRaw = tempRaw.toInt();
      int tRaw2 = 0;
      if(tRaw <= 900)
      {
        tRaw2 = tRaw;
      }
      if(tRaw > 900)
      {
        tRaw2 = 900 + 5*(tRaw-900);
      }
      int tFinal = (tRaw2-400)/10;
      //Serial.println(tFinal);
      String temp = (String) tFinal;
      String die = (String) dielectric;
      int dieRaw = die.toInt();
      int VWC = (2.25*(pow(10,-5))*(pow(dieRaw,3))) - (2.06*(pow(10,-3))*(pow(dieRaw,2))) + (7.24*(pow(10,-2))*dieRaw) - 0.247;
      //Serial.println(VWC);
      String vwc = (String) VWC;
      String rt = (String) rtt;
      String rs = (String) rssi;
      String nhID = (String) node.nextHop_ID;
      String final = "params=" + sinkID + "," + packetType + "," + packetLength + "," + sourceID + "," + temp + "," + vwc + "," + rt + "," + rs + "," + t + "," +  ws + "," + h + "," + wg + "," + r + "," + nhID + "," + d;
      Serial.println(final);
      const char * data3 = final.c_str();
      postData(data3);
      //getData();
    }
    
    if (radio.ACKRequested()) //sending packet acknowledgement
    {
      byte theNodeID = radio.SENDERID;
      radio.sendACK();
    }
    
  }
  
  radio.receiveDone(); //putting radio in RX mode
  Serial.flush(); // flushing serial port before receivig data
  //LowPower.powerDown(SLEEP_FOREVER, ADC_OFF, BOD_OFF);// putting arduino to sleep until radio interrupt is received
  
}

void postData(const char* data3)
{ 
  static const char* url = "134.102.188.200/deploy.php"; 
  time1 = millis();
  for (;;) 
  {
    if (gprs.httpInit()) 
    {
      Serial.print("HTTP Connection");
      Serial.println(gprs.buffer);
      break;
    }
    Serial.println(gprs.buffer);
    gprs.httpUninit();
    time2 = millis();
    //Serial.print("time 1 ");
    //Serial.println(time1);
    //Serial.print("time 2 ");
    //Serial.println(time2);
    //Serial.print("Time for http Connection: ");
    diff1 = time2 - time1;
    //Serial.println(diff1);
    if(diff1 >= 60000)
    {
      Serial.print("Time for http Connection: ");
      Serial.println(diff1);
      time1 = millis();
      Serial.println("Connection Lost");
      for (;;) 
      {
        Serial.print("Resetting GSM Shield...");
        while (!gprs.init()) 
        {
          Serial.write('.');
        }
        Serial.println("OK");
    
        Serial.print("Setting up network...");
        byte ret = gprs.setup(APN);
        if (ret == 0)
        {
          break;
        }
        Serial.print("Error code:");
        Serial.println(ret);
        Serial.println(gprs.buffer);
       } 
      delay(3000);
      if (gprs.getOperatorName()) 
      {
        Serial.print("Operator:");
        Serial.println(gprs.buffer);
       }
      int ret = gprs.getSignalQuality();
      if (ret) 
      {
        Serial.print("Signal:");
        Serial.print(ret);
        Serial.println("dB");
      }
    }
    delay(1000);
  }
  delay(3000);
  gprs.httpConnect(url);
  time3 = millis();
  for (;;)
  {
    byte check = gprs.httpIsConnected();
    if(check == 0)
    {
       //Serial.println("chal raha hai");
       time4 = millis();
       //Serial.print("time 3 ");
       //Serial.println(time3);
       //Serial.print("time 4 ");
       //Serial.println(time4);
       //Serial.print("Establishing link : ");
       diff2 = time4 - time3;
       //Serial.println(diff2);
       if(diff2 >= 60000)
       {
         Serial.print("Time for link Connection : ");
         Serial.println(diff2);
         time3 = millis();
         check = 2;
         Serial.println("Connection Lost");
       } 
    }
    if(check == 1)
    {
      Serial.println("Cool");
      Serial.println(gprs.buffer);
      break;
    }
    if(check == 2)
    {
      Serial.println("Not cool");
      Serial.println(gprs.buffer);
      for (;;) 
      {
        Serial.print("Resetting GSM Shield...");
        while (!gprs.init()) 
        {
          Serial.write('.');
        }
        Serial.println("OK");
    
        Serial.print("Setting up network...");
        byte ret = gprs.setup(APN);
        if (ret == 0)
        {
          break;
        }
        Serial.print("Error code:");
        Serial.println(ret);
        Serial.println(gprs.buffer);
      } 
      delay(3000);
      if (gprs.getOperatorName()) 
      {
        Serial.print("Operator:");
        Serial.println(gprs.buffer);
      }
      int ret = gprs.getSignalQuality();
      if (ret) 
      {
        Serial.print("Signal:");
        Serial.print(ret);
        Serial.println("dB");
      }
      for (;;) 
      {
        if (gprs.httpInit()) 
        {
          Serial.print("HTTP Connection");
          Serial.println(gprs.buffer);
          break;
        }
        Serial.println(gprs.buffer);
        gprs.httpUninit();
        delay(1000);
      }
      delay(3000);
      gprs.httpConnect(url);
    }
  }
  gprs.sendCommand("AT+HTTPPARA=CONTENT,application/x-www-form-urlencoded");
  delay(100);
  Serial.println(gprs.buffer);
  gprs.sendCommand("AT+HTTPDATA=192,10000");
  delay(100);
  Serial.println(gprs.buffer);
  gprs.sendCommand(data3);
  delay(10000);
  gprs.sendCommand("AT+HTTPACTION=1");
  delay(5000);
  Serial.println(gprs.buffer);
  gprs.sendCommand("AT+HTTPREAD");
  delay(10000);
  Serial.println(gprs.buffer);
  int len = strlen(gprs.buffer);
  //Serial.println(len);
  Serial.print("shuru");
  Serial.print(gprs.buffer[16]);
  Serial.println("khatam");
  if(gprs.buffer[16] == '1')
  {
    Serial.println("LED ON");
    digitalWrite (LED, HIGH);
  }
  if(gprs.buffer[16] == '2')
  {
    Serial.println("LED OFF");
    digitalWrite (LED, LOW);
  }
  gprs.sendCommand("AT+HTTPTERM");
  delay(500);
  Serial.println(gprs.buffer);
  Serial.println("Done");
}

void postWeather(const char* data4)
{ 
  static const char* url = "134.102.188.200/deploy2.php"; 
  time5 = millis();
  for (;;) 
  {
    if (gprs.httpInit()) 
    {
      Serial.print("HTTP Connection");
      Serial.println(gprs.buffer);
      break;
    }
    Serial.println(gprs.buffer);
    gprs.httpUninit();
    time6 = millis();
    //Serial.print("time 1 ");
    //Serial.println(time1);
    //Serial.print("time 2 ");
    //Serial.println(time2);
    //Serial.print("Time for http Connection: ");
    diff3 = time5 - time6;
    //Serial.println(diff1);
    if(diff3 >= 60000)
    {
      Serial.print("Time for http Connection: ");
      Serial.println(diff3);
      time5 = millis();
      Serial.println("Connection Lost");
      for (;;) 
      {
        Serial.print("Resetting GSM Shield...");
        while (!gprs.init()) 
        {
          Serial.write('.');
        }
        Serial.println("OK");
    
        Serial.print("Setting up network...");
        byte ret = gprs.setup(APN);
        if (ret == 0)
        {
          break;
        }
        Serial.print("Error code:");
        Serial.println(ret);
        Serial.println(gprs.buffer);
       } 
      delay(3000);
      if (gprs.getOperatorName()) 
      {
        Serial.print("Operator:");
        Serial.println(gprs.buffer);
       }
      int ret = gprs.getSignalQuality();
      if (ret) 
      {
        Serial.print("Signal:");
        Serial.print(ret);
        Serial.println("dB");
      }
    }
    delay(1000);
  }
  delay(3000);
  gprs.httpConnect(url);
  time7 = millis();
  for (;;)
  {
    byte check = gprs.httpIsConnected();
    if(check == 0)
    {
       //Serial.println("chal raha hai");
       time8 = millis();
       //Serial.print("time 3 ");
       //Serial.println(time3);
       //Serial.print("time 4 ");
       //Serial.println(time4);
       //Serial.print("Establishing link : ");
       diff4 = time8 - time7;
       //Serial.println(diff2);
       if(diff2 >= 60000)
       {
         Serial.print("Time for link Connection : ");
         Serial.println(diff4);
         time7 = millis();
         check = 2;
         Serial.println("Connection Lost");
       } 
    }
    if(check == 1)
    {
      Serial.println("Cool");
      Serial.println(gprs.buffer);
      break;
    }
    if(check == 2)
    {
      Serial.println("Not cool");
      Serial.println(gprs.buffer);
      for (;;) 
      {
        Serial.print("Resetting GSM Shield...");
        while (!gprs.init()) 
        {
          Serial.write('.');
        }
        Serial.println("OK");
    
        Serial.print("Setting up network...");
        byte ret = gprs.setup(APN);
        if (ret == 0)
        {
          break;
        }
        Serial.print("Error code:");
        Serial.println(ret);
        Serial.println(gprs.buffer);
      } 
      delay(3000);
      if (gprs.getOperatorName()) 
      {
        Serial.print("Operator:");
        Serial.println(gprs.buffer);
      }
      int ret = gprs.getSignalQuality();
      if (ret) 
      {
        Serial.print("Signal:");
        Serial.print(ret);
        Serial.println("dB");
      }
      for (;;) 
      {
        if (gprs.httpInit()) 
        {
          Serial.print("HTTP Connection");
          Serial.println(gprs.buffer);
          break;
        }
        Serial.println(gprs.buffer);
        gprs.httpUninit();
        delay(1000);
      }
      delay(3000);
      gprs.httpConnect(url);
    }
  }
  gprs.sendCommand("AT+HTTPPARA=CONTENT,application/x-www-form-urlencoded");
  delay(100);
  Serial.println(gprs.buffer);
  gprs.sendCommand("AT+HTTPDATA=192,10000");
  delay(100);
  Serial.println(gprs.buffer);
  gprs.sendCommand(data4);
  delay(10000);
  gprs.sendCommand("AT+HTTPACTION=1");
  delay(5000);
  Serial.println(gprs.buffer);
  gprs.sendCommand("AT+HTTPREAD");
  delay(10000);
  Serial.println(gprs.buffer);
  gprs.sendCommand("AT+HTTPTERM");
  delay(500);
  Serial.println(gprs.buffer);
  Serial.println("Done"); 
}

