-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2025 at 05:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klinik_alya_medika`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `queue_number` int NOT NULL,
  `status` enum('Menunggu','Diterima','Selesai','Batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `complaint` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `queue_number`, `status`, `complaint`, `created_at`, `updated_at`) VALUES
(1, 7, 3, '2025-10-29', '10:30:00', 8, 'Selesai', 'Sakit gigi dan gusi bengkak', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(2, 7, 2, '2025-10-17', '08:30:00', 2, 'Menunggu', 'Nyeri sendi dan otot', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(3, 8, 2, '2025-10-16', '14:00:00', 5, 'Diterima', 'Batuk kering dan sesak napas', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(4, 8, 4, '2025-10-24', '11:00:00', 9, 'Diterima', 'Sakit tenggorokan dan suara serak', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(5, 9, 4, '2025-10-06', '09:00:00', 9, 'Batal', 'Sakit gigi dan gusi bengkak', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(6, 9, 3, '2025-10-05', '16:00:00', 8, 'Selesai', 'Sakit gigi dan gusi bengkak', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(7, 9, 3, '2025-10-12', '15:00:00', 6, 'Diterima', 'Gangguan tidur dan kelelahan', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(8, 10, 3, '2025-10-13', '09:00:00', 6, 'Selesai', 'Demam tinggi selama 3 hari', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(9, 10, 3, '2025-10-10', '11:30:00', 4, 'Menunggu', 'Nyeri perut bagian bawah', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(10, 10, 1, '2025-10-28', '15:30:00', 6, 'Batal', 'Nyeri perut bagian bawah', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(11, 11, 4, '2025-10-21', '10:30:00', 7, 'Diterima', 'Nyeri sendi dan otot', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(12, 11, 1, '2025-10-10', '16:00:00', 10, 'Diterima', 'Ruam kulit dan gatal-gatal', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(13, 11, 2, '2025-10-08', '08:30:00', 5, 'Menunggu', 'Demam tinggi selama 3 hari', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(14, 13, 5, '2025-11-04', '12:00:00', 1, 'Selesai', 'adasa', '2025-11-03 04:25:20', '2025-11-03 04:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `specialty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `specialty`, `schedule`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 3, 'Penyakit Dalam', '{\"Senin\":{\"start\":\"08:00\",\"end\":\"12:00\"},\"Rabu\":{\"start\":\"08:00\",\"end\":\"12:00\"},\"Jumat\":{\"start\":\"08:00\",\"end\":\"12:00\"}}', 1, '2025-11-03 03:05:41', '2025-11-03 03:05:41'),
(2, 4, 'Kebidanan dan Kandungan', '{\"Selasa\":{\"start\":\"09:00\",\"end\":\"13:00\"},\"Kamis\":{\"start\":\"09:00\",\"end\":\"13:00\"},\"Sabtu\":{\"start\":\"09:00\",\"end\":\"13:00\"}}', 1, '2025-11-03 03:05:41', '2025-11-03 03:05:41'),
(3, 5, 'Anak', '{\"Senin\":{\"start\":\"13:00\",\"end\":\"17:00\"},\"Rabu\":{\"start\":\"13:00\",\"end\":\"17:00\"},\"Jumat\":{\"start\":\"13:00\",\"end\":\"17:00\"}}', 1, '2025-11-03 03:05:41', '2025-11-03 03:05:41'),
(4, 6, 'Jantung dan Pembuluh Darah', '{\"Selasa\":{\"start\":\"14:00\",\"end\":\"18:00\"},\"Kamis\":{\"start\":\"14:00\",\"end\":\"18:00\"},\"Sabtu\":{\"start\":\"14:00\",\"end\":\"18:00\"}}', 1, '2025-11-03 03:05:41', '2025-11-03 03:05:41'),
(5, 12, 'makan', '{\"Senin\":{\"start\":\"08:00\",\"end\":\"17:00\"},\"Selasa\":{\"start\":\"08:00\",\"end\":\"17:00\"}}', 1, '2025-11-03 03:07:06', '2025-11-03 03:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `medical_record_id` bigint UNSIGNED NOT NULL,
  `consultation_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `medication_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `treatment_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Belum Lunas','Lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Lunas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `patient_id`, `medical_record_id`, `consultation_fee`, `medication_fee`, `treatment_fee`, `other_fee`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 50000.00, 164785.00, 41307.00, 38388.00, 294480.00, 'Belum Lunas', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(2, 9, 2, 50000.00, 44044.00, 58151.00, 78102.00, 230297.00, 'Belum Lunas', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(3, 10, 3, 50000.00, 137008.00, 179146.00, 5618.00, 371772.00, 'Lunas', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(4, 13, 4, 50000.00, 0.00, 0.00, 0.00, 50000.00, 'Lunas', '2025-11-03 05:04:11', '2025-11-03 05:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` enum('rawat_jalan','rawat_inap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `prescription_data` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `appointment_id`, `patient_id`, `doctor_id`, `complaint`, `diagnosis`, `treatment`, `service_type`, `prescription_data`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 3, 'Sakit gigi dan gusi bengkak', 'Infeksi saluran pernapasan atas', 'Suplemen zat besi dan vitamin C', 'rawat_inap', NULL, 'Pasien dalam kondisi stabil dan dapat melanjutkan aktivitas normal.', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(2, 6, 9, 3, 'Sakit gigi dan gusi bengkak', 'Gangguan kecemasan', 'Inhalasi dan ekspektoran', 'rawat_jalan', 'Paracetamol 500mg 3x1 sehari setelah makan', 'Pasien dalam kondisi stabil dan dapat melanjutkan aktivitas normal.', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(3, 8, 10, 3, 'Demam tinggi selama 3 hari', 'Gangguan kecemasan', 'Obat pereda nyeri dan relaksasi', 'rawat_jalan', 'Ibuprofen 400mg 3x1 sehari jika nyeri', 'Pasien dalam kondisi stabil dan dapat melanjutkan aktivitas normal.', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(4, 14, 13, 5, 'tes', 'tes', 'tes', 'rawat_jalan', '[{\"name\":\"Ibuprofen\",\"dosage\":\"400mg\",\"quantity\":\"10\",\"instructions\":\"3x1 sehari setelah makan\"},{\"name\":\"Amoxicillin\",\"dosage\":\"250mg\",\"quantity\":\"10\",\"instructions\":\"3x1 sehari setelah makan\"}]', NULL, '2025-11-03 04:26:04', '2025-11-03 04:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_doctors_table', 1),
(5, '2024_01_01_000002_create_appointments_table', 1),
(6, '2024_01_01_000003_create_medical_records_table', 1),
(7, '2024_01_01_000004_create_prescriptions_table', 1),
(8, '2024_01_01_000005_create_prescription_items_table', 1),
(9, '2024_01_01_000006_create_referral_letters_table', 1),
(10, '2024_01_01_000007_create_invoices_table', 1),
(11, '2024_01_01_000008_modify_users_table', 1),
(12, '2025_01_01_000000_modify_doctors_table', 1),
(13, '2025_10_22_221703_add_prescription_to_medical_records_table', 1),
(14, '2025_10_27_150126_update_doctors_schedule_to_json', 1),
(15, '2025_10_27_194435_create_report_histories_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `medical_record_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `prescription_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `medical_record_id`, `patient_id`, `prescription_number`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 'RX000002', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(2, 3, 10, 'RX000003', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(3, 4, 13, 'RX000004', '2025-11-03 04:26:04', '2025-11-03 04:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `prescription_id` bigint UNSIGNED NOT NULL,
  `medicine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `instructions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescription_items`
--

INSERT INTO `prescription_items` (`id`, `prescription_id`, `medicine_name`, `dosage`, `quantity`, `instructions`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paracetamol 500mg', '500mg', 13, '3x1 sehari setelah makan', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(2, 1, 'Amoxicillin 500mg', '500mg', 21, '3x1 sehari selama 7 hari', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(3, 2, 'Paracetamol 500mg', '500mg', 21, '3x1 sehari setelah makan', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(4, 2, 'Amoxicillin 500mg', '500mg', 33, '3x1 sehari selama 7 hari', '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(5, 3, 'Ibuprofen', '400mg', 10, '3x1 sehari setelah makan', '2025-11-03 04:26:04', '2025-11-03 04:26:04'),
(6, 3, 'Amoxicillin', '250mg', 10, '3x1 sehari setelah makan', '2025-11-03 04:26:04', '2025-11-03 04:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `referral_letters`
--

CREATE TABLE `referral_letters` (
  `id` bigint UNSIGNED NOT NULL,
  `medical_record_id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `referral_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referred_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_letters`
--

INSERT INTO `referral_letters` (`id`, `medical_record_id`, `patient_id`, `referral_number`, `referred_to`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 'REF000001', 'RS Mayapada Jakarta', 'Perlu pemeriksaan laboratorium dan radiologi lanjutan', '2025-11-03 03:05:43', '2025-11-03 03:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `report_histories`
--

CREATE TABLE `report_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('c9x8axYOEy7gMHFnHe8xutQUMx6v8oRtjrZWNaCm', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYWxDWXpoT2dsSHVFc1M5QkY1M1I2M256eEJESjBhQU05Y0xIZFJiMiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcGF0aWVudC9oaXN0b3J5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', 1762146328),
('SlGCv34XOfJfoNbhQas8t82mFCDz6NcI8e9kYhNj', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMGplblVCWmhJYWJsTjgzZ0F1OWhZU0RycVdHUjZCcnV5b2I3S0VQcCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2RvY3RvcnMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2ludm9pY2VzLzQvcHJpbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1762146314);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','pasien','dokter') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pasien',
  `address` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `nik`, `role`, `address`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin2', 'admin@alyamedika.com', '081234567890', '1234567890123456', 'admin', 'Jl. Klinik Alya Medika No. 123, Jakarta', '2025-11-03 03:05:39', '$2y$12$IvfadpEfq82RfRy6W99gReQUx3dn5NSUYPwOxjmcX6.p3kVdeebHa', NULL, '2025-11-03 03:05:39', '2025-11-03 03:05:39'),
(2, 'Administrator', 'admin', 'admin@gmail.com', '081234567891', '1234567890123457', 'admin', 'Jl. Klinik Alya Medika No. 123, Jakarta', '2025-11-03 03:05:40', '$2y$12$uUtdyN3qbR1tQA3GcxEXducaDdRatss73EClUfY9W2tJ.L1Nx57Fi', NULL, '2025-11-03 03:05:40', '2025-11-03 03:05:40'),
(3, 'Dr. Ahmad Wijaya, Sp.PD', 'ahmad.wijaya', 'ahmad.wijaya@alyamedika.com', '081234567890', '0000000000000000', 'dokter', 'Klinik Alya Medika', '2025-11-03 03:05:40', '$2y$12$Ykr4VnRel3X4wESEDCMameh71b3tw.WbaDCDKkbkB4sI5gFfUD4Fq', NULL, '2025-11-03 03:05:40', '2025-11-03 03:05:40'),
(4, 'Dr. Siti Nurhaliza, Sp.OG', 'siti.nurhaliza', 'siti.nurhaliza@alyamedika.com', '081234567891', '0000000000000000', 'dokter', 'Klinik Alya Medika', '2025-11-03 03:05:40', '$2y$12$eg.s0UxGgNbuheh9ZR4g7u3OL4dXezEWUYkVGLcLUICWi6fnFL0zu', NULL, '2025-11-03 03:05:40', '2025-11-03 03:05:40'),
(5, 'Dr. Budi Santoso, Sp.A', 'budi.santoso', 'budi.santoso@alyamedika.com', '081234567892', '0000000000000000', 'dokter', 'Klinik Alya Medika', '2025-11-03 03:05:40', '$2y$12$Gu0bLaOsN0KptwHUyhAC7OsFmDw0BQDWUVFgd5XQI1XZdGL7GfCGC', NULL, '2025-11-03 03:05:40', '2025-11-03 03:05:40'),
(6, 'Dr. Maria Magdalena, Sp.JP', 'maria.magdalena', 'maria.magdalena@alyamedika.com', '081234567893', '0000000000000000', 'dokter', 'Klinik Alya Medika', '2025-11-03 03:05:41', '$2y$12$uPtmPVebLh5MayFcvK4zoe64kgmnErgi4.TG9.FXYfjW32EtcsoxC', NULL, '2025-11-03 03:05:41', '2025-11-03 03:05:41'),
(7, 'Ahmad Wijaya', NULL, 'ahmad.wijaya@email.com', '081234567890', '3171234567890123', 'pasien', 'Jl. Merdeka No. 123, Jakarta Pusat', NULL, '$2y$12$o4lcSJgsCxENzF4urI0onuVz1Qe4soQVbvAWikSWSmhI8LKvXm0Aq', NULL, '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(8, 'Siti Nurhaliza', NULL, 'siti.nurhaliza@email.com', '081234567891', '3171234567890124', 'pasien', 'Jl. Sudirman No. 456, Jakarta Selatan', NULL, '$2y$12$gMeEMebH7Z3J4Rc.JNx.5.OYY9NjuSAZ9e5YCHL0lGLYpSRh6a9me', NULL, '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(9, 'Budi Santoso', NULL, 'budi.santoso@email.com', '081234567892', '3171234567890125', 'pasien', 'Jl. Thamrin No. 789, Jakarta Pusat', NULL, '$2y$12$WrAzGsjqZlQk28csnQ17DuzAME4X126/G.cT5585GiRbAhU1Qsfzy', NULL, '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(10, 'Dewi Kartika', NULL, 'dewi.kartika@email.com', '081234567893', '3171234567890126', 'pasien', 'Jl. Gatot Subroto No. 321, Jakarta Selatan', NULL, '$2y$12$W12VRBhc1ZkVR9zi2DFAGuIzppNe0WSrsVC/z3WaF4odcYZE2wCyG', NULL, '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(11, 'Rizki Pratama', NULL, 'rizki.pratama@email.com', '081234567894', '3171234567890127', 'pasien', 'Jl. Kebon Jeruk No. 654, Jakarta Barat', NULL, '$2y$12$VMk/cd/s3qp2jkqdw6Mha.GX6N9muDcEZMtGVdNrT9ZzFjbXj.ezu', NULL, '2025-11-03 03:05:43', '2025-11-03 03:05:43'),
(12, 'testing', 'testing123', 'testing@gmail.com', '0888888', '0000000000000000', 'dokter', 'Klinik Alya Medika', NULL, '$2y$12$rnKTUAzwI/uaPrFWdS4uVervWJUObF2KzZ0qDnwL3Zt54CR/2XXMS', NULL, '2025-11-03 03:06:49', '2025-11-03 03:06:49'),
(13, 'pasien', NULL, 'pasien@gmail.com', '0822', '1111111111111114', 'pasien', 'blang pulo', NULL, '$2y$12$waTOi//cFRFnXtkXL8D1MeXFLqgrnsXax4IVKHCUPvHURIfORXZ1m', NULL, '2025-11-03 04:16:01', '2025-11-03 04:16:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_patient_id_foreign` (`patient_id`),
  ADD KEY `invoices_medical_record_id_foreign` (`medical_record_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_records_appointment_id_foreign` (`appointment_id`),
  ADD KEY `medical_records_patient_id_foreign` (`patient_id`),
  ADD KEY `medical_records_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prescriptions_prescription_number_unique` (`prescription_number`),
  ADD KEY `prescriptions_medical_record_id_foreign` (`medical_record_id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_items_prescription_id_foreign` (`prescription_id`);

--
-- Indexes for table `referral_letters`
--
ALTER TABLE `referral_letters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_letters_referral_number_unique` (`referral_number`),
  ADD KEY `referral_letters_medical_record_id_foreign` (`medical_record_id`),
  ADD KEY `referral_letters_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `report_histories`
--
ALTER TABLE `report_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `referral_letters`
--
ALTER TABLE `referral_letters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_histories`
--
ALTER TABLE `report_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_records_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_letters`
--
ALTER TABLE `referral_letters`
  ADD CONSTRAINT `referral_letters_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referral_letters_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
