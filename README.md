# Flight Search Aggregator & Booking Engine (Backend API)

A production-grade, highly extensible Laravel backend service that aggregates flight schedules concurrently from multiple disparate third-party providers, filters duplicates by best-fare matrix operations, and exposes clean RESTful booking pipelines.

---

## Architectural Highlights & Patterns

- **Action/Domain Pattern:** Keeps controllers ultra-thin by isolating single business capabilities into standalone orchestrators (`SearchFlightsAction`, `CreateBookingAction`).
- **Strategy & Adapter Pattern:** Decouples volatile downstream vendor schemas (`ProviderA`, `ProviderB`, etc.) via a unified `FlightProviderInterface` and strict type-safe `FlightDTO` mappings.
- **Stateless Unique Token Identifiers:** Flight IDs exposed to the client are tamper-proof, deterministic Base64 tokens containing route metadata. This bypasses stateful database caching overhead during multi-step checkout processes.
- **Deduplication Engine:** Evaluates duplicates across vendors on the fly using composite identifier signatures (`Carrier-FlightNo-DepartureTime`) and preserves only the cheapest option.
- **In-Memory Mock Orchestration:** Bypasses PHP single-threaded local development deadlocks (cURL timeout 28) by using abstract loop hooks while preserving an identical architectural pipeline for live HTTP client pooling.

---

## System Requirements

- **PHP:** >= 8.2
- **Composer:** >= 2.x
- **Extensions required:** `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`
- **Database Engine:** SQLite (Default for lightweight portable setups)

---

## Installation & Setup Guide

Follow these sequential steps to install and boot the service locally:

### 1. Clone the Repository
```bash
git clone [https://github.com/your-username/flight-search-aggregator.git](https://github.com/your-username/flight-search-aggregator.git)
cd flight-search-aggregator
```


### 2. Install Package Dependencies
```bash
composer install
```

### 3. Configure Environment Environment
```bash
cp .env.example .env
```

### 4. Generate Application Encription Key
```bash
php artisan key:generate
```

### 5. Initialize SQLite Database Storage
```bash
# For Linux / macOS
touch database/database.sqlite

# For Windows (PowerShell)
New-Item database/database.sqlite -ItemType File

# Run Laravel Migration Schemes
php artisan migrate
```

Running the Application
```bash
php artisan serve
```

The application will boot up at http://127.0.0.1:8000.

# API Testing Guide (Postman Setup)

## 1. Unified Flight Search

* **Method:** `GET`
* **URL:** `http://127.0.0.1:8000/api/v1/flights/search`
* **Query Parameters (`Params`):**

| Key | Value | Description |
| :--- | :--- | :--- |
| `from` | `DAC` | 3-Letter Origin IATA Code |
| `to` | `DXB` | 3-Letter Destination IATA Code |
| `date` | `2026-07-01` | Departure Date (`YYYY-MM-DD`) |
| `passengers` | `2` | Number of seats multiplier (Optional) |
| `sort_by` | `price` | Sort index (`price` or `duration`, Optional) |

---

### full url

```text
http://127.0.0.1:8000/api/v1/flights/search?from=DAC&to=DXB&date=2026-07-01&passengers=2&sort_by=price
```

* **Expected Output:** Returns a sorted list of deduplicated flights. Copy the `id` string (Base64 token) from any flight object to perform a booking test.

## 2. Confirm a Flight Booking

* **Method:** `POST`
* **URL:** `http://127.0.0.1:8000/api/v1/bookings`
* **Headers:** Add `Accept: application/json`
* **Body Tab:** Select `raw`, switch dropdown type to `JSON`, and paste:

---
```json
{
  "flight_id": "PASTE_THE_COPIED_BASE64_TOKEN_HERE",
  "passengers": [
    {
      "first_name": "Ahammed",
      "last_name": "Imtiaze",
      "passport_number": "BG1234567"
    }
  ]
}
```


### Formatting Details:
* **JSON Syntax Highlighting:** Enclosed the request body inside a proper Markdown code block marked with `json` to ensure clean syntax color rendering in MkDocs.
* **Inline Code Badges:** Captured response statuses (`201 Created`), field references (`reference`), and HTTP methods (`GET`) in backticks (` ` `).
* **Dynamic URL Paths:** Kept the placeholder `{YOUR_GENERATED_REFERENCE}` inside the inline URL code block to maintain clarity for technical documentation.
