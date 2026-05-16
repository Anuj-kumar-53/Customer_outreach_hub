<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\BusinessVerificationController as AdminBusinessVerificationController;
use App\Http\Controllers\Admin\CampaignModerationController as AdminCampaignModerationController;
use App\Http\Controllers\Admin\CommentModerationController as AdminCommentModerationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotificationCenterController as AdminNotificationCenterController;
use App\Http\Controllers\Admin\ReportManagementController as AdminReportManagementController;
use App\Http\Controllers\Admin\UserManagementController as AdminUserManagementController;
use App\Http\Controllers\BusinessDashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignFeedController;
use App\Http\Controllers\CampaignLikeController;
use App\Http\Controllers\SavedCampaignController;
use App\Http\Controllers\CampaignCommentController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\CustomerReferralController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PublicCampaignController;
use App\Http\Controllers\BusinessAnalyticsController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Public campaign landing + referral capture (guest session only)
Route::get('/campaign/{campaign}', [PublicCampaignController::class, 'show'])->name('campaign.public');

// Default Breeze dashboard (kept for compatibility)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Super Admin Panel ────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [AdminUserManagementController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/unsuspend', [AdminUserManagementController::class, 'unsuspend'])->name('users.unsuspend');
    Route::post('/users/{user}/ban', [AdminUserManagementController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserManagementController::class, 'unban'])->name('users.unban');
    Route::delete('/users/{user}', [AdminUserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/businesses', [AdminBusinessVerificationController::class, 'index'])->name('businesses.index');
    Route::post('/businesses/{business}/verify', [AdminBusinessVerificationController::class, 'verify'])->name('businesses.verify');
    Route::post('/businesses/{business}/unverify', [AdminBusinessVerificationController::class, 'unverify'])->name('businesses.unverify');

    Route::get('/campaigns', [AdminCampaignModerationController::class, 'index'])->name('campaigns.index');
    Route::post('/campaigns/{campaign}/remove', [AdminCampaignModerationController::class, 'remove'])->name('campaigns.remove');
    Route::post('/campaigns/{campaign}/restore', [AdminCampaignModerationController::class, 'restore'])->name('campaigns.restore');

    Route::get('/comments', [AdminCommentModerationController::class, 'index'])->name('comments.index');
    Route::post('/comments/{comment}/approve', [AdminCommentModerationController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{comment}/hide', [AdminCommentModerationController::class, 'hide'])->name('comments.hide');
    Route::post('/comments/{comment}/spam', [AdminCommentModerationController::class, 'markSpam'])->name('comments.spam');
    Route::delete('/comments/{comment}', [AdminCommentModerationController::class, 'destroy'])->name('comments.destroy');

    Route::get('/reports', [AdminReportManagementController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [AdminReportManagementController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/status', [AdminReportManagementController::class, 'status'])->name('reports.status');

    Route::get('/notifications', [AdminNotificationCenterController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [AdminNotificationCenterController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationCenterController::class, 'readAll'])->name('notifications.read-all');

    Route::get('/activity', [AdminActivityLogController::class, 'index'])->name('activity.index');
});

// ─── Business Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:business'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [BusinessDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [BusinessAnalyticsController::class, 'index'])->name('analytics');
    Route::resource('campaigns', CampaignController::class)->except(['show']);
});

// ─── Customer Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/referrals', [CustomerReferralController::class, 'index'])->name('referrals');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::post('/reports', [CustomerReportController::class, 'store'])->name('reports.store');
});

Route::middleware(['auth', 'role:customer'])->get('/campaign-feed', [CampaignFeedController::class, 'index'])->name('customer.campaign-feed');

// Phase 6 — Likes, Saves & Comments (customer-only)
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Likes
    Route::post('/campaigns/{campaign}/like', [CampaignLikeController::class, 'store'])->name('campaigns.like');
    Route::delete('/campaigns/{campaign}/like', [CampaignLikeController::class, 'destroy'])->name('campaigns.unlike');

    // Saves
    Route::post('/campaigns/{campaign}/save', [SavedCampaignController::class, 'store'])->name('campaigns.save');
    Route::delete('/campaigns/{campaign}/save', [SavedCampaignController::class, 'destroy'])->name('campaigns.unsave');

    // Comments
    Route::post('/campaigns/{campaign}/comments', [CampaignCommentController::class, 'store'])->name('campaigns.comments.store');
    Route::delete('/campaign-comments/{comment}', [CampaignCommentController::class, 'destroy'])->name('campaigns.comments.destroy');
});

require __DIR__ . '/auth.php';
