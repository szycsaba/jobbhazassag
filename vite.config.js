import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/all.min.css",
                "resources/css/article.css",
                "resources/css/fonts.css",
                "resources/css/variables.css",
                "resources/css/normalize.css",
                "resources/css/webflow.css",
                "resources/css/subscribe.css",
                "resources/css/style.css",
                "resources/js/app.js",
                "resources/js/article.js",
                "resources/js/article-form.js",
                "resources/js/article-reflection-notes.js",
                "resources/js/answer_carousel.js",
                "resources/js/bootstrap.js",
                "resources/js/carousel.js",
                "resources/js/CarouselHandler.js",
                "resources/js/drag-drop-reorder.js",
                "resources/js/email_modal.js",
                "resources/js/header-management.js",
                "resources/js/invite.js",
                "resources/js/logout-handler.js",
                "resources/js/notification-handler.js",
                "resources/js/onboarding-reason.js",
                "resources/js/onboarding-signin-disabler.js",
                "resources/js/onboarding-welcome.js",
                "resources/js/quiz-handler.js",
                "resources/js/quiz-question-manager.js",
                "resources/js/reflection-form.js",
                "resources/js/script.js",
                "resources/js/test_carousel.js",
                "resources/js/welcome.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
