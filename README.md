# Gitlab webhook notifications
#### Via telegram bot

![title](storage/app/public/example.png)

## Telegram
### Create bot
- Create bot via [@BotFather](https://telegram.me/BotFather) 
- Invite bot to the channel

## Run Project

### Add channel and bot props to project .env file
```dotenv
TELEGRAM_BOT_HOST=https://api.telegram.org/bot
TELEGRAM_BOT_TOKEN="bot_id:token"
TELEGRAM_BOT_TIMEOUT=7
TELEGRAM_HASH_CHAT_IDS="ab487e9d750a3c50876d12e8f381a79f:-1001234567890;some_hash_2:some_chat_id_2"
```

### Docker
```dockerfile
docker-compose up -d
docker exec -it gitlab-notification-app composer install
```

### Database
```bash
docker exec -it gitlab-notification-app php artisan migrate
```

## Gitlab

#### Allow localhost bot

- Admin Area -> Settings -> Network -> Outbound requests
```
[x] Allow requests to the local network from webhooks and integrations
```

#### Add URL to Gitlab Webhook Settings

![title](storage/app/public/webhook.png)
