cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-available/default
cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-enabled/default
cp /home/site/wwwroot/PipelineAzureController.php /home/site/wwwroot/src/Controller/AzureController.php
service nginx reload
