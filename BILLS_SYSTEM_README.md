# Bills Management System - JAWARA API

## Overview
System manajemen tagihan (bills) untuk aplikasi mobile JAWARA dengan workflow approval yang lengkap. Sistem ini memungkinkan pembuatan tagihan otomatis dari kategori iuran ke seluruh keluarga yang aktif, dengan proses verifikasi pembayaran oleh admin.

## Business Workflow

### Payment Flow
```
1. UNPAID     → User uploads payment proof → PENDING
2. PENDING    → Admin approves             → PAID
3. PENDING    → Admin rejects              → REJECTED  
4. REJECTED   → User re-uploads proof      → PENDING
5. UNPAID     → System marks overdue       → OVERDUE
6. OVERDUE    → User uploads proof         → PENDING
```

### Status Explanation
- **UNPAID**: Tagihan baru yang belum dibayar
- **PENDING**: User sudah upload bukti bayar, menunggu verifikasi admin
- **PAID**: Admin sudah approve pembayaran
- **REJECTED**: Admin menolak bukti bayar dengan alasan tertentu
- **OVERDUE**: Tagihan terlambat (melewati periode)

## Features Implemented

### 1. Database Structure
- **Migration**: `2025_12_01_163117_create_bills_table.php`
- **Fields**:
  - `id`: Primary key
  - `code`: Kode unik tagihan (IR202512XXXX)
  - `family_id`: FK ke tabel families
  - `income_category_id`: FK ke tabel income_categories
  - `periode`: Periode tagihan (tanggal)
  - `amount`: Nominal tagihan
  - `status`: Status pembayaran (`unpaid`, `pending`, `paid`, `rejected`, `overdue`)
  - `payment_proof`: Path bukti pembayaran
  - `paid_at`: Tanggal user upload bukti bayar
  - `verified_by`: Admin yang approve/reject
  - `verified_at`: Tanggal verifikasi
  - `rejection_reason`: Alasan penolakan (untuk status rejected)
  - `created_by`: User pembuat tagihan

### 2. Model & Relations
- **BillModel.php**: Model untuk bills dengan relations
  - `family()`: Belongs to FamilyModel
  - `incomeCategory()`: Belongs to IncomeCategoryModel  
  - `creator()`: Belongs to User (pembuat)
  - `verifier()`: Belongs to User (verifikator)
  - **Scopes**: unpaid, pending, paid, rejected, overdue, forFamily, forIncomeCategory, forPeriode
  - **Accessors**: formattedAmount, statusLabel, periodeLabel

### 3. Business Logic Layer
- **BillService.php**: Service class untuk business logic
  - `generateBillsForCategory()`: Generate tagihan untuk satu kategori
  - `generateBillsForCategories()`: Generate untuk multiple kategori
  - `uploadPaymentProof()`: User upload bukti bayar (unpaid/rejected → pending)
  - `approvePayment()`: Admin approve pembayaran (pending → paid)
  - `rejectPayment()`: Admin reject dengan alasan (pending → rejected)
  - `markOverdueBills()`: Update status jadi overdue
  - `getPaymentStatistics()`: Statistik pembayaran

### 4. API Layer
- **BillController.php**: RESTful controller dengan methods:
  - `index()`: List bills dengan filtering & pagination
  - `store()`: Create bill baru
  - `show()`: Detail bill
  - `update()`: Update bill
  - `destroy()`: Delete bill
  - `generateBills()`: Generate bills untuk kategori
  - `uploadPaymentProof()`: User upload bukti bayar
  - `approvePayment()`: Admin approve pembayaran
  - `rejectPayment()`: Admin reject dengan alasan
  - `statistics()`: Get payment statistics
  - `markOverdue()`: Mark overdue bills

### 5. Request Validation
- **BillRequest.php**: Validasi untuk CRUD operations
- **GenerateBillRequest.php**: Validasi untuk generate bills

### 6. API Resources
- **BillResource.php**: Format response JSON untuk bills

### 7. Sample Data
- **BillSeeder.php**: Generate sample data bills untuk testing
  - 65+ sample bills dengan berbagai status
  - Bills untuk 3 bulan terakhir
  - Realistic distribution: 44.6% paid, 18.5% pending, 13.8% rejected, 13.8% overdue, 9.2% unpaid
  - Test bills dengan berbagai scenario workflow
  - Rejection reasons untuk rejected bills

## API Endpoints

```
# Basic CRUD
GET    /api/bills                      # List bills dengan filtering
POST   /api/bills                      # Create new bill  
GET    /api/bills/{id}                 # Get bill detail
PUT    /api/bills/{id}                 # Update bill
DELETE /api/bills/{id}                 # Delete bill

# Bill Generation
POST   /api/bills/generate             # Generate bills untuk kategori

# User Actions
PATCH  /api/bills/{id}/upload-payment  # Upload bukti bayar (unpaid → pending)

# Admin Actions
PATCH  /api/bills/{id}/approve-payment # Approve pembayaran (pending → paid)
PATCH  /api/bills/{id}/reject-payment  # Reject dengan alasan (pending → rejected)

# Reports & Statistics
GET    /api/bills/statistics           # Get payment statistics
POST   /api/bills/mark-overdue         # Mark overdue bills
```

## Query Filters
- `family_id`: Filter by family
- `income_category_id`: Filter by income category
- `status`: Filter by status (unpaid, paid, overdue)
- `periode`: Filter by specific periode
- `periode_from` & `periode_to`: Filter by date range
- `search`: Search by code, family name, or category name
- `per_page`: Pagination limit

## Test Results
✅ **65 Bills Created Successfully**

✅ **Bills Distribution by Status**:
   - **PAID**: 29 bills (44.6%) - Rp 2,945,000
   - **PENDING**: 12 bills (18.5%) - Rp 1,365,000 (waiting admin verification)
   - **REJECTED**: 9 bills (13.8%) - Rp 1,525,000 (with rejection reasons)
   - **OVERDUE**: 9 bills (13.8%) - Rp 590,000
   - **UNPAID**: 6 bills (9.2%) - Rp 430,000

✅ **Workflow Validation**:
   - ✓ User can upload payment proof for unpaid/rejected/overdue bills
   - ✓ Admin can approve pending bills → status changes to paid
   - ✓ Admin can reject pending bills with reason → status changes to rejected
   - ✓ Rejected bills show rejection reason to user
   - ✓ Users can re-upload proof for rejected bills

✅ **Relations Working**: Family, Income Category, Creator, Verifier relations
✅ **Service Layer**: All business logic methods working properly
✅ **Model Scopes**: All filtering scopes (unpaid, pending, paid, rejected, overdue)

## Action Items Summary

### For Users (Families)
- **6 Unpaid Bills**: Need to make payment and upload proof
- **9 Rejected Bills**: Need to re-upload valid payment proof
- **9 Overdue Bills**: Late payment, need immediate action

### For Admin
- **12 Pending Bills**: Need to verify and approve/reject

## Usage Example

### 1. Generate Bills
```http
POST /api/bills/generate
Content-Type: application/json
Authorization: Bearer {token}

{
  "income_category_id": 1,
  "periode": "2024-12-01"
}
```

### 2. User Upload Payment Proof
```http
PATCH /api/bills/{id}/upload-payment
Content-Type: application/json
Authorization: Bearer {token}

{
  "payment_proof": "uploads/payments/bukti_bayar_123.jpg"
}
```

### 3. Admin Approve Payment
```http
PATCH /api/bills/{id}/approve-payment
Authorization: Bearer {admin_token}
```

### 4. Admin Reject Payment
```http
PATCH /api/bills/{id}/reject-payment
Content-Type: application/json
Authorization: Bearer {admin_token}

{
  "rejection_reason": "Bukti pembayaran tidak jelas, mohon upload ulang dengan foto yang lebih jelas"
}
```

### 5. List Bills with Filters
```http
GET /api/bills?status=pending&family_id=1&per_page=20
Authorization: Bearer {token}
```

## Business Rules

1. **Upload Payment Proof**:
   - Only bills with status `unpaid`, `rejected`, or `overdue` can upload payment proof
   - After upload, status changes to `pending`
   - Previous rejection reason is cleared

2. **Approve Payment**:
   - Only bills with status `pending` can be approved
   - After approval, status changes to `paid`
   - Verifier and verification timestamp are recorded

3. **Reject Payment**:
   - Only bills with status `pending` can be rejected
   - Rejection reason is mandatory
   - After rejection, status changes to `rejected`
   - User can re-upload new proof

4. **Overdue Bills**:
   - System automatically marks unpaid bills as `overdue`
   - User can still upload payment proof for overdue bills

## Status
✅ **COMPLETE** - Bills management system fully implemented and tested