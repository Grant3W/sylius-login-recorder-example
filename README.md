# Sylius Login Recorder Example
This simple event listener class will log administrator logins to a CSV file. This is a very basic example that should be expanded upon before being used in a production site.

## Installation
- Add the `LoginRecorder.php` file to the `src/LoginRecorder` directory.
- Add the following snippet to your `config/services.yaml`.

```yaml
App\LoginRecorder\LoginRecorder:
        tags:
            - {name: kernel.event_listener, event: security.interactive_login, method: recordLoginEvent}
```

## Usage
The next time someone logs in, you'll find the `admin-logins.csv` file inside the `public/` directory.

This will contain the time, username, IP address and user agent of the login.

## Next Steps  
This is an example. If I were making this for a production site, I would consider the following:
- The log shouldn't be in the `public` directory exposed to the internet.
- You could give users the option to download the log from within the dashboard.
- Finally, there should be error-handling and tests in place.
