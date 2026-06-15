# 🚀 Laravel 13 API Gateway

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red?logo=laravel)](https://laravel.com)
[![Passport](https://img.shields.io/badge/Passport-OAuth2-blue?logo=laravel)](https://laravel.com/docs/passport)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777bb4?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

A robust API gateway built with **Laravel 13** and **Laravel Passport**, designed to unify microservices (user, order, payment) under a single secure entry point. Provides OAuth2 token authentication, rate limiting, smart routing, and observability – ready to serve **React SPA** and **mobile apps**.

---

## ✨ Features

- 🔐 **Laravel Passport OAuth2** – issue access/refresh tokens, scoped permissions, PKCE support
- 🚦 **Smart Routing** – dynamic proxying to microservices (User, Order, Payment)
- ⏱️ **Rate Limiting** – Redis‑backed throttling per client/token
- 📡 **Observability** – request tracing, metrics, structured logging
- 📱 **Unified Entry** – single domain for all microservices, consumed by React & mobile clients
- 🐳 **Docker Ready** – ship with PHP 8.4‑FPM, Nginx, Redis, MySQL

---

## 🏗️ Architecture
