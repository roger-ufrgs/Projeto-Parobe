# Copilot instructions for Parobe

Purpose
- Help AI coding agents be productive quickly when working on this PHP + JS sensor-monitoring project.

Big picture
- Sensor data path: MQTT clients (ESP32) -> `js/mqtt.js` (browser MQTT) or backend ingestion -> `salvar_dados.php` -> MySQL (`parobe` DB). UI reads aggregated data via endpoints like `query.php` and renders charts in `index.php` using `js/charts.js` and Chart.js.
- IA integration: front-end `js/ia.js` calls `gpt.php` (server-side proxy) which posts to OpenAI. `gpt.php` currently contains a hard-coded API key — do not expose secrets; prefer environment variables or server-side config.

Key files to inspect
- `DataBase.php` — central PDO singleton. DB credentials and `parobe` database are hard-coded here; update before running.
- `query.php` — example SQL pattern: uses window functions and averages last N rows per sensor. Keep similar query structure when adding aggregations.
- `gpt.php` — OpenAI call and JSON passthrough. Front-end expects the OpenAI response shape (it accesses `choices[0].message.content`).
- `js/ia.js` — shows how the front-end fetches `gpt.php` and parses the response.
- `index.php` + `js/main.js` — app entry: imports charts, mqtt, status, and IA test helpers. Use these imports when adding features.
- `js/mqtt.js`, `salvar_dados.php`, `status_mysql.php` — integration points for telemetry, persistence, and health checks.

Conventions and patterns
- Endpoints return JSON. Avoid returning HTML in API endpoints consumed by `fetch` calls.
- PHP uses simple require/endpoint files (no framework). Keep `$pdo = DataBase::connect()` usage to share DB connection.
- Front-end uses ES modules and Chart.js loaded via CDN. Keep module exports/imports consistent (`export` / `import`).
- Error handling: code tends to `die()` on DB errors; prefer returning JSON error objects for API endpoints if expanding features.

Running & debugging (no build system)
- Start a local PHP server from the project root during development:
  php -S localhost:8000
- MySQL: database `parobe` expected. `DataBase.php` contains `localhost`, user `root`, pass `root` by default — change to match your environment.
- Debug steps: open browser console, inspect network calls to `query.php`, `gpt.php`, and MQTT websocket activity. Check MQTT connect status shown in `index.php` footer.

Security notes
- `gpt.php` contains a value that looks like an OpenAI API key. Remove keys from source control and use environment variables or a secure server store.

Editing guidance for AI agents
- Preserve existing endpoint names and JSON shapes when changing API behavior, especially `gpt.php` (front-end expects `choices[0].message.content`) and `query.php` (front-end expects an array of sensor averages).
- When modifying DB schema or queries, update `query.php` and any code that aggregates recent rows (they assume the last ~30 rows per sensor).
- When adding features, follow the existing pattern: small PHP endpoints that `echo json_encode(...)` and JS modules that `fetch()` them.

If anything in this guide is unclear or you want more detail for a specific task (tests, CI, or refactor), tell me which area and I'll expand the instructions.
