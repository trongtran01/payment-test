# Dự án Test Payment

Đây là dự án **PHP + Docker** để thử nghiệm các phương thức thanh toán khác nhau (Stripe & PayPal) trong môi trường local.  
Mục đích là để nghiên cứu và test tích hợp thanh toán một cách an toàn.

---

## Tính năng

- Test **Stripe Checkout** (thanh toán bằng thẻ)  
- Test **PayPal Sandbox** (thanh toán thử)  
- **Cash on Delivery (COD)** như phương thức placeholder  
- Môi trường Docker hóa với PHP & Apache  
- Biến môi trường được quản lý bằng file `.env`

---

## Yêu cầu

- Docker & Docker Compose
- PHP 8.2
- Composer

---

## Cài đặt

1. **Clone repository**

```bash
git clone https://github.com/trongtran01/payment-test.git
cd payment-test
