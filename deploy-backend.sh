#!/bin/bash
cd /home/bobflows/htdocs/www.bobflows.com
echo "==== Vue frontend deploy started at $(date) ====" >> /home/bobflows/deploy.log
/usr/bin/git pull origin main >> /home/bobflows/deploy.log 2>&1
/usr/bin/npm install --legacy-peer-deps >> /home/bobflows/deploy.log 2>&1
/usr/bin/npm run build >> /home/bobflows/deploy.log 2>&1
/usr/bin/pm2 restart bobflows >> /home/bobflows/deploy.log 2>&1
echo "==== Vue frontend deploy completed ====" >> /home/bobflows/deploy.log
