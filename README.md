Application app2 : Regional Advisories Import

php bin/console app:import-json-file var/liste-des-conseillers-regionaux.json

Application app1 : php bin/console messenger:consume async

php bin/console doctrine:migrations:migrate

Project 1: http://127.0.0.1:8001/api/regions