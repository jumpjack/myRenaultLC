// Up to date data for Italy:
newData = {
// Downloaded from https://renault-wrd-prod-1-euw1-myrapp-one.s3-eu-west-1.amazonaws.com/configuration/android/config_it_IT.json
// NOTE: Kamereon key is obsolete: you must retrieve it from inside the smartphone app
	"servers": {
        "wiredProd": { 
            "target": "https://api-wired-prod-1-euw1.wrd-aws.com",
            "apikey": "Ae9FDWugRxZQAGm3Sxgk7uJn6Q4CGEA2" // Valid as of september 2021
        },
         "gigyaProd": { 
            "target": "https://accounts.eu1.gigya.com",
            "apikey": "3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq"
        }
    },
};

///////
data = {
// Old data downloaded from https://renault-wrd-prod-1-euw1-myrapp-one.s3-eu-west-1.amazonaws.com/configuration/android/config_it_IT.json
// NOTE: Kamereon key is obsolete: you must retrieve it from inside the smartphone app
	"servers": {
        "kamareon": {
            "target": "https://alliance-platform-serviceadapter-staging.apps.prod.eu.kamereon.org",
            "apikey": "Z7P4xyNrTITzNZh52oObSOVfKns9XGLE"
        },
        "wired": {
            "target": "https://api-wired-dev-1-euw1.wrd-aws.com",
            "apikey": "AHdOWFASWEPUVQVlhJWshsios0FqTG2E"
        },
        "wiredValid": {
            "target": "https://api-wired-valid-1-euw1.wrd-aws.com",
            "apikey": "AHdOWFASWEPUVQVlhJWshsios0FqTG2E"
        },
        "wiredProd": { // <<----  Now the apikey is obsolete, use instead Ae9FDWugRxZQAGm3Sxgk7uJn6Q4CGEA2 (valid as of september 2021)
            "target": "https://api-wired-prod-1-euw1.wrd-aws.com",
            "apikey": "oF09WnKqvBDcrQzcW1rJNpjIuy7KdGaB"
        },
        "gigya": {
            "target": "https://accounts.eu1.gigya.com",
            "apikey": "3_S0OWIrqeJ6mxOkXFT8i3TTDwW1IGKk2rIypZjGXi4Hh8vce2ohuERio1Ka5DBbUr"
        },
        "gigyaValid": {
            "target": "https://accounts.eu1.gigya.com",
            "apikey": "3_7zpQr-4uTyP8xAbB7qK_yTdpNRvNzjw4XKaRnFOkDloiaWf5WkzPxI8ROrchCXvQ"
        },
        "gigyaProd": { // <<-----------------
            "target": "https://accounts.eu1.gigya.com",
            "apikey": "3_js8th3jdmCWV86fKR3SXQWvXGKbHoWFv8NAgRbH7FnIBsi_XvCpN_rtLcI07uNuq"
        }
    },
};
