
# Secure Web Application


## Architecture Overview

**Request flow:**

```
Client (HTTPS)
   ↓
Caddy (HTTPS, Reverse Proxy)
   ↓
WAF (ModSecurity CRS)
   ↓
Website (PHP)
   ↓
MariaDB
```

Only **Caddy** is exposed to the outside. All other services are internal.

---

## Docker Compose Setup

```yaml
version: "3.9"

services:
  db:
    image: mariadb
    environment:
      MYSQL_ROOT_PASSWORD: supersecure
      MYSQL_DATABASE: customers
    volumes:
      - ./sql-scripts:/docker-entrypoint-initdb.d

  website:
    build:
      context: .
      dockerfile: Dockerfile
    expose:
      - "80"
    volumes:
      - ./html:/var/www/html

  waf:
    image: owasp/modsecurity-crs:apache
    depends_on:
      - website
    expose:
      - "8080"
    environment:
      BACKEND: "http://website:80"
      PROXY: "1"
      PARANOIA: "1"
      ANOMALY_INBOUND: "10"
      ANOMALY_OUTBOUND: "5"

  caddy:
    image: caddy:2-alpine
    depends_on:
      - waf
    ports:
      - "8443:443"
    volumes:
      - ./Caddyfile:/etc/caddy
```
* **Database** is never exposed
* **Website** is only reachable via WAF
* **WAF** is internal-only and enforces OWASP CRS rules
* **Caddy** is the only public entry point

---

## Web Application Firewall (WAF)

The WAF uses **OWASP ModSecurity Core Rule Set** to protect against:

* SQL Injection
* XSS
* Path Traversal
* Protocol abuse

Configured with:

* Paranoia Level 1
* Defined anomaly thresholds to block malicious requests

---

## HTTPS & Reverse Proxy (Caddy)

```caddyfile
{
    debug
}

https://localhost {
    tls internal
    reverse_proxy http://waf:8080
}
```

### Purpose

* Enforces **HTTPS-only** access
* Terminates TLS
* Forwards all traffic to the WAF
* Uses **internal TLS certificates** (development/testing)

If needed, I can further reduce this to a **one-page README**, or adapt it to a **school assignment or security concept document**.
