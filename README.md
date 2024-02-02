# Gitlab webhook notifications
## Telegram bot

### Create bot
- Create bot via [@BotFather](https://telegram.me/BotFather) 
- Invite bot to the channel


### Add channel and bot props to .env file
```dotenv
TELEGRAM_BOT_HOST=https://api.telegram.org/bot
TELEGRAM_BOT_TOKEN="bot:token"
TELEGRAM_BOT_TIMEOUT=7
TELEGRAM_HASH_CHAT_IDS="hash:chat_id;hash_2:chat_id_2"
```

### Gitlab allow localhost bot

- Admin Area -> Settings -> Network -> Outbound requests
```
[x] Allow requests to the local network from webhooks and integrations
```

- Add Webhook to Gitlab

### Docker
```dockerfile
docker-compose up -d
docker exec -it gitlab-notification-app composer install
```

### Database
```bash
docker exec -it gitlab-notification-app php artisan migrate
```
