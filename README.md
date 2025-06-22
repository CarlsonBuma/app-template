# Webapp Template
by Carlson, 2025-06-25

## Folder Structure
Root folder:
   - /backend (see Readme.md)
   - /docs
   - /frontend (see Readme.md)
   - Start Environment - "start in terminal":
      > "./env-start.ps1"
   - Setup Docker Environment - "docker compose up":
      > "./docker-compose.yml"

## Security Check
   - go "/frontend": 
      - npm update
      - npm audit
      - npm audit fix --force
   - go "/backend": 
      - composer outdated
      - composer update

## Go Live - Server Configurations
Backend (Laravel12) & Frontend (Node.js)
Ref. readme.ssh.md
