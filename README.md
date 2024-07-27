## Notification-server

The `notification-server` service within the <b>Phober</b> project manages the sending of various notifications such as emails, OTPs, and support messages. It integrates with communication platforms like Discord and Telegram. This service also includes a `notification-queue` component in Docker Compose, enabling asynchronous message handling through job queues to optimize responsiveness.

### Responsibilities:
- Sends emails, OTPs, and support messages via Discord, Telegram, etc.
- Utilizes a `notification-queue` to manage message delivery asynchronously, optimizing performance and responsiveness.

### Dependencies:
- Depends on the `config-server` and `adminpanel` services for configuration and data management.
- Utilizes the `phober_notification` database for data operations.

### Environment Variables

The following environment variables can be configured via the `.env` file inside the notification-server directory or provided through Docker Compose:

- `DISCORD_TOKEN`: The token for authenticating with the Discord API.
- `DISCORD_SUPPORT_CHANNEL`: The channel ID for support messages in Discord.
- `DISCORD_OTP_CHANNEL`: The channel ID for OTP messages in Discord.
- `TELEGRAM_BOT_TOKEN`: The token for authenticating with the Telegram Bot API.
- `TELEGRAM_OTP_CHANNEL`: The channel ID for OTP messages in Telegram.
- `TELEGRAM_SUPPORT_CHANNEL`: The channel ID for support messages in Telegram.

### Additional Details:
- Enhances message delivery efficiency using job queues within the Docker environment.
- Integrates with various communication platforms for versatile notification capabilities.
