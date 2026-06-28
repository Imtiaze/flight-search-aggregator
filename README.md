# Flight Search Aggregator & Booking Engine (Backend API)

A production-grade, highly extensible Laravel backend service that aggregates flight schedules concurrently from multiple disparate third-party providers, filters duplicates by best-fare matrix operations, and exposes clean RESTful booking pipelines.

## Architectural Highlights & Patterns

- **Action/Domain Pattern:** Keeps controllers ultra-thin by isolating single business capabilities into standalone orchestrators (`SearchFlightsAction`, `CreateBookingAction`).
- **Strategy & Adapter Pattern:** Decouples volatile downstream vendor schemas (`ProviderA`, `ProviderB`, etc.) via a unified `FlightProviderInterface` and strict type-safe `FlightDTO` mappings.
- **Stateless Unique Token Identifiers:** Flight IDs exposed to the client are tamper-proof, deterministic Base64 tokens containing route metadata. This bypasses stateful database caching overhead during multi-step checkout processes.
- **Deduplication Engine:** Evaluates duplicates across vendors on the fly using composite identifier signatures (`Carrier-FlightNo-DepartureTime`) and preserves only the cheapest option.
- **In-Memory Mock Orchestration:** Bypasses PHP single-threaded local development deadlocks (cURL timeout 28) by using abstract loop hooks while preserving an identical architectural pipeline for live HTTP client pooling.


## System Requirements

- **PHP:** >= 8.2
- **Composer:** >= 2.x
- **Extensions required:** `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`
- **Database Engine:** SQLite (Default for lightweight portable setups)


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


### full url

```text
http://127.0.0.1:8000/api/v1/flights/search?from=DAC&to=DXB&date=2026-07-01&passengers=2&sort_by=price
```

* **Expected Output:** 

```json
{
    "metadata": {
        "provider_completeness": {
            "ProviderAService": {
                "status": "success",
                "code": 200
            },
            "ProviderBService": {
                "status": "success",
                "code": 200
            },
            "ProviderCService": {
                "status": "success",
                "code": 200
            }
        },
        "total_results": 4
    },
    "data": [
        {
            "id": "eyJzaWciOiJBQS1BQTIwNS0yMDI2MDcwMTIyMTAiLCJwcnYiOiJQcm92aWRlckFTZXJ2aWNlIiwidmFsIjoyODB9",
            "carrier": "AA",
            "flight_number": "AA205",
            "origin": "DAC",
            "destination": "DXB",
            "departure_time": "2026-07-01T22:10:00+00:00",
            "arrival_time": "2026-07-02T02:40:00+00:00",
            "duration_minutes": 270,
            "stops": 0,
            "price": {
                "amount": 560,
                "currency": "USD"
            }
        },
        {
            "id": "eyJzaWciOiJCUy1CUzIyMC0yMDI2MDcwMTA5MTUiLCJwcnYiOiJQcm92aWRlckFTZXJ2aWNlIiwidmFsIjozMTB9",
            "carrier": "BS",
            "flight_number": "BS220",
            "origin": "DAC",
            "destination": "DXB",
            "departure_time": "2026-07-01T09:15:00+00:00",
            "arrival_time": "2026-07-01T15:00:00+00:00",
            "duration_minutes": 345,
            "stops": 1,
            "price": {
                "amount": 620,
                "currency": "USD"
            }
        },
        {
            "id": "eyJzaWciOiJBQS1BQTEwMS0yMDI2MDcwMTA4MDAiLCJwcnYiOiJQcm92aWRlckFTZXJ2aWNlIiwidmFsIjozMjB9",
            "carrier": "AA",
            "flight_number": "AA101",
            "origin": "DAC",
            "destination": "DXB",
            "departure_time": "2026-07-01T08:00:00+00:00",
            "arrival_time": "2026-07-01T12:30:00+00:00",
            "duration_minutes": 270,
            "stops": 0,
            "price": {
                "amount": 640,
                "currency": "USD"
            }
        },
        {
            "id": "eyJzaWciOiJFSy1FSzU4NS0yMDI2MDcwMTAzNDUiLCJwcnYiOiJQcm92aWRlckFTZXJ2aWNlIiwidmFsIjo0MTB9",
            "carrier": "EK",
            "flight_number": "EK585",
            "origin": "DAC",
            "destination": "DXB",
            "departure_time": "2026-07-01T03:45:00+00:00",
            "arrival_time": "2026-07-01T06:50:00+00:00",
            "duration_minutes": 185,
            "stops": 0,
            "price": {
                "amount": 820,
                "currency": "USD"
            }
        }
    ]
}
```

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
Expected Output
```json
{
    "data": {
        "reference": "IBXRNIIYBCI",
        "flight_signature": "EK-EK585-202607010345",
        "provider": "ProviderAService",
        "total_fare_usd": 820,
        "passengers": [
            {
                "first_name": "Ahammed",
                "last_name": "Imtiaze",
                "passport_number": "BG1234567"
            },
            {
                "first_name": "Rahat",
                "last_name": "Khan",
                "passport_number": "BG7654321"
            }
        ],
        "created_at": "2026-06-28T17:01:31+00:00"
    },
    "message": "Booking confirmed successfully."
}
```

## 3. Retrieve Booking Records

* **Method:** `GET`
* **URL:** `http://127.0.0.1:8000/api/v1/bookings/{YOUR_GENERATED_REFERENCE}`

Expected Output:

```json
{
    "data": {
        "reference": "IBXAPBI8KXI",
        "flight_signature": "flight_key",
        "provider": "proivder_booked",
        "total_fare_usd": 100,
        "passengers": [
            {
                "first_name": "Ahammed",
                "last_name": "Imtiaze",
                "passport_number": "BG1234567"
            },
            {
                "first_name": "Rahat",
                "last_name": "Khan",
                "passport_number": "BG7654321"
            }
        ],
        "created_at": "2026-06-28T06:29:00+00:00"
    }
}
```
