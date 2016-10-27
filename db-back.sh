#!/bin/bash

# Backup entire database
mysqldump -u root -p --all-databases > /vagrant/SQLFiles/alldb_backup.sql
