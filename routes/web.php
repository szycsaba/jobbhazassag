<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\DashboardArticleController;
use App\Http\Controllers\DashboardReflectionController;
use App\Http\Controllers\DashboardQuizController;
use App\Http\Controllers\DashboardOnboardingController;
use App\Http\Controllers\Dashboard\DashboardStatisticsController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () { return view('public.index');})->name('home');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
Route::get('thank-you/{session_id}', [ThankYouController::class, 'show'])->name('thank-you');
Route::post('/invite', [InviteController::class, 'store'])->name('invite.store');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->middleware('subscriber.access')->name('article.show');
Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');
Route::get('/subscriber-warning', function () { return view('public.subscriber-warning'); })->name('subscriber.warning');
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('redirect.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/auth/google/logout', [GoogleController::class, 'logout'])->name('google.logout');
Route::post('/onboarding/save-reason', [OnboardingController::class, 'saveReason'])->name('onboarding.save-reason');
Route::post('/article/{slug}/save-reflection-notes', [ArticleController::class, 'saveReflectionNotes'])->name('article.save-reflection-notes');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['admin-auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['admin-auth', 'verified']], function () {
    Route::get('/dashboard-articles', [DashboardArticleController::class, 'list'])->name('dashboard-articles');
    Route::get('/dashboard-articles/create', [DashboardArticleController::class, 'createArticle'])->name('dashboard-articles.create-article');
    Route::post('/dashboard-articles/store', [DashboardArticleController::class, 'storeArticle'])->name('dashboard-articles.store-article');
    Route::delete('/dashboard-articles/{slug}/delete', [DashboardArticleController::class, 'destroyArticle'])->name('dashboard-articles.destroy-article');
    Route::get('/dashboard-articles/{slug}', [DashboardArticleController::class, 'show'])->name('dashboard-articles.show');
    Route::get('/dashboard-articles/{slug}/add', [DashboardArticleController::class, 'create'])->name('dashboard-articles.create');
    Route::post('/dashboard-articles/{slug}/store', [DashboardArticleController::class, 'store'])->name('dashboard-articles.store');
    Route::get('/dashboard-articles/{slug}/edit/{id}', [DashboardArticleController::class, 'edit'])->name('dashboard-articles.edit');
    Route::patch('/dashboard-articles/{slug}/update/{id}', [DashboardArticleController::class, 'update'])->name('dashboard-articles.update');
    Route::delete('/dashboard-articles/{slug}/delete/{id}', [DashboardArticleController::class, 'destroy'])->name('dashboard-articles.destroy');
    Route::post('/dashboard-articles/{slug}/reorder', [DashboardArticleController::class, 'reorder'])->name('dashboard-articles.reorder');

    // Article Images routes
    Route::get('/dashboard-article-images', [DashboardArticleController::class, 'articleImages'])->name('dashboard-article-images');
    Route::post('/dashboard-article-images/upload', [DashboardArticleController::class, 'uploadImages'])->name('dashboard-article-images.upload');
    Route::delete('/dashboard-article-images/delete', [DashboardArticleController::class, 'deleteImage'])->name('dashboard-article-images.delete');

    // Article Type Color routes
    Route::post('/dashboard-article-type-colors/update', [DashboardArticleController::class, 'updateTypeColors'])->name('dashboard-article-type-colors.update');
    Route::post('/dashboard-article-type-colors/reset', [DashboardArticleController::class, 'resetTypeColors'])->name('dashboard-article-type-colors.reset');

    // Reflections dashboard
    Route::get('/dashboard-reflections', [DashboardReflectionController::class, 'list'])->name('dashboard-reflections');
    Route::get('/dashboard-reflections/create', [DashboardReflectionController::class, 'createReflection'])->name('dashboard-reflections.create-reflection');
    Route::post('/dashboard-reflections/store', [DashboardReflectionController::class, 'store'])->name('dashboard-reflections.store');
    Route::delete('/dashboard-reflections/{id}', [DashboardReflectionController::class, 'destroyReflection'])->name('dashboard-reflections.destroy-reflection');
    Route::get('/dashboard-reflections/{id}', [DashboardReflectionController::class, 'show'])->name('dashboard-reflections.show');
    Route::get('/dashboard-reflections/{id}/add', [DashboardReflectionController::class, 'create'])->name('dashboard-reflections.create');
    Route::post('/dashboard-reflections/{id}/store', [DashboardReflectionController::class, 'storeQuestion'])->name('dashboard-reflections.store-question');
    Route::post('/dashboard-reflections/{id}/reorder', [DashboardReflectionController::class, 'reorder'])->name('dashboard-reflections.reorder');
    Route::delete('/dashboard-reflections/{id}/delete/{question_id}', [DashboardReflectionController::class, 'destroyQuestion'])->name('dashboard-reflections.destroy-question');
    Route::get('/dashboard-reflections/{id}/edit/{question_id}', [DashboardReflectionController::class, 'edit'])->name('dashboard-reflections.edit');
    Route::put('/dashboard-reflections/{id}/update/{question_id}', [DashboardReflectionController::class, 'update'])->name('dashboard-reflections.update');

    // Quiz dashboard
    Route::get('/dashboard-quiz', [DashboardQuizController::class, 'list'])->name('dashboard-quiz');
    Route::get('/dashboard-quiz/create', [DashboardQuizController::class, 'createQuiz'])->name('dashboard-quiz.create');
    Route::post('/dashboard-quiz/store', [DashboardQuizController::class, 'store'])->name('dashboard-quiz.store');
    Route::get('/dashboard-quiz/{id}', [DashboardQuizController::class, 'show'])->name('dashboard-quiz.show');
    Route::get('/dashboard-quiz/{id}/edit', [DashboardQuizController::class, 'edit'])->name('dashboard-quiz.edit');
    Route::put('/dashboard-quiz/{id}/update', [DashboardQuizController::class, 'update'])->name('dashboard-quiz.update');
    Route::get('/dashboard-quiz/{id}/add', [DashboardQuizController::class, 'createQuestion'])->name('dashboard-quiz.create-question');
    Route::post('/dashboard-quiz/{id}/store', [DashboardQuizController::class, 'storeQuestion'])->name('dashboard-quiz.store-question');
    Route::get('/dashboard-quiz/{id}/edit/{question_id}', [DashboardQuizController::class, 'editQuestion'])->name('dashboard-quiz.edit-question');
    Route::put('/dashboard-quiz/{id}/update/{question_id}', [DashboardQuizController::class, 'updateQuestion'])->name('dashboard-quiz.update-question');
    Route::delete('/dashboard-quiz/{id}/delete/{question_id}', [DashboardQuizController::class, 'destroyQuestion'])->name('dashboard-quiz.destroy-question');
    Route::delete('/dashboard-quiz/{id}', [DashboardQuizController::class, 'destroy'])->name('dashboard-quiz.destroy');

    // Onboarding dashboard
    Route::get('/dashboard-onboarding', [DashboardOnboardingController::class, 'index'])->name('dashboard-onboarding');
    Route::get('/dashboard-onboarding/create', [DashboardOnboardingController::class, 'create'])->name('dashboard-onboarding.create');
    Route::post('/dashboard-onboarding/store', [DashboardOnboardingController::class, 'store'])->name('dashboard-onboarding.store');
    Route::get('/dashboard-onboarding/{id}/edit', [DashboardOnboardingController::class, 'edit'])->name('dashboard-onboarding.edit');
    Route::patch('/dashboard-onboarding/{id}/update', [DashboardOnboardingController::class, 'update'])->name('dashboard-onboarding.update');
    Route::delete('/dashboard-onboarding/{id}/delete', [DashboardOnboardingController::class, 'destroy'])->name('dashboard-onboarding.destroy');
    Route::post('/dashboard-onboarding/reorder', [DashboardOnboardingController::class, 'reorder'])->name('dashboard-onboarding.reorder');
    
    // Header management
    Route::post('/dashboard/header/save', [DashboardArticleController::class, 'saveHeader'])->name('dashboard.header.save');
    Route::get('/dashboard/header/images', [DashboardArticleController::class, 'getAvailableImages'])->name('dashboard.header.images');
    
    // Statistics route
    Route::get('/dashboard/statistics', [DashboardStatisticsController::class, 'index'])->name('dashboard.statistics');
    Route::get('/dashboard/statistics/export', [DashboardStatisticsController::class, 'export'])->name('dashboard.statistics.export');
});

Route::middleware('admin-auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


