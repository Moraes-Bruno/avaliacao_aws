#include <pgmspace.h>

#define SECRET
#define THINGNAME "ESP32_POLICY"

const char WIFI_NAME[] = "WifiName";                                                        
const char WIFI_PASS[] = "WifiPass";                                                  
const char AWS_IOT_ENDPOINT[] = "IotEndpoint";  

// Amazon Root CA 1
static const char AWS_CERT_CA[] PROGMEM = R"EOF(

-----BEGIN CERTIFICATE-----

-----END CERTIFICATE-----

)EOF";

// Device Certificate  (CRT)
static const char AWS_CERT_CRT[] PROGMEM = R"KEY(

-----BEGIN CERTIFICATE-----

-----END CERTIFICATE-----



)KEY";

// Device Private Key                                            
static const char AWS_CERT_PRIVATE[] PROGMEM = R"KEY(
  
-----BEGIN RSA PRIVATE KEY-----

-----END RSA PRIVATE KEY-----


)KEY";