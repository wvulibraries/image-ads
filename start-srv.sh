#!/bin/bash

## Variables
APP_NAME="Rotating-Homepage-Ads"

cd /vagrant/${APP_NAME}
bin/rails server -b 0.0.0.0
