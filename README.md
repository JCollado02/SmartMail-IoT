# SmartMail-IoT
This final project was for my IoT (Internet of Things) course, in which I had worked with @jacobm19 to create a mailbox that was capable of 
detecting when a package has been left inside and reports the time of delivery to a database which is shown on web dashboard. The box also features two servos. One servo acts as a lock mechanism and the other controls the flag. Both of which are triggered after motion is detected
and the box is closed. In total, this project uses an ESP8266 to control the servos and PIR sensor, a Raspberry Pi to host the webpage and contain the database, two servos, and a PIR sensor. In this project, 
my partner and had to utilize our knowledge in SQL, PHP, HTML, Python, and C to create our dashboard and setup our devices.

## Devices Used
- Raspberry Pi 4
- Two MG90S Servos
- ESP8266
- PIR Sensor

## Creating Our Smart Mailbox
For our project, our Raspberry Pi is responsible for hosting the web dashboard used to display the times that packages were delivered and for controlling the PIR sensor and servos. Each entry into the database simply contains a timestamp of when something was delivered and the name of the device. The database was created on the Pi using MariaDB and the webpage was created using HTML, a little CSS, JavaScript, and PHP.
