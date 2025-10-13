document.addEventListener('DOMContentLoaded', function() {
    const quizContainers = document.querySelectorAll('.quizBox');
    
    quizContainers.forEach(container => {
        initQuiz(container);
    });
});

function initQuiz(container) {
    const questions = container.querySelectorAll('.quiz-question');
    const progressFills = container.querySelectorAll('.quiz-progress-fill');
    const counters = container.querySelectorAll('.quiz-counter');
    
    let currentQuestion = 0;
    const totalQuestions = questions.length;
    
    // Initialize quiz
    updateQuizDisplay();
    
    // Force cursor pointer on all answer boxes
    const answerBoxes = container.querySelectorAll('.answerBox');
    answerBoxes.forEach(box => {
        box.style.cursor = 'pointer';
        const pElement = box.querySelector('p');
        if (pElement) {
            pElement.style.cursor = 'pointer';
        }
    });
    
    // Handle answer selection
    container.addEventListener('click', function(e) {
        if (e.target.closest('.answerBox')) {
            const answerBox = e.target.closest('.answerBox');
            const question = answerBox.closest('.quiz-question');
            const questionIndex = parseInt(question.dataset.question);
            
            // Prevent multiple clicks
            if (question.classList.contains('answered')) {
                return;
            }
            
            // Mark question as answered
            question.classList.add('answered');
            
            // Remove previous selection from this question
            question.querySelectorAll('.answerBox').forEach(box => {
                box.classList.remove('selected', 'correct', 'incorrect', 'correct-answer');
            });
            
            // Add selection to clicked answer
            answerBox.classList.add('selected');
            
            // Check if the selected answer is correct
            const isCorrect = answerBox.getAttribute('data-is-correct') === '1';
            
            // Force inline styles based on correctness
            if (isCorrect) {
                answerBox.classList.add('correct');
                answerBox.style.backgroundColor = '#dcfce7'; // Green for correct
                answerBox.style.border = '2px solid #16a34a';
                answerBox.style.boxShadow = '0 0 10px rgba(34, 197, 94, 0.3)';
            } else {
                answerBox.classList.add('incorrect');
                answerBox.style.backgroundColor = '#fecaca'; // Red for wrong
                answerBox.style.border = '2px solid #dc2626';
                answerBox.style.boxShadow = '0 0 10px rgba(220, 38, 38, 0.3)';
            }
            
            // Always show the correct answer
            highlightCorrectAnswer(question);
            
            // Store the answer
            const questionId = question.querySelector('h5').textContent;
            const answerText = answerBox.querySelector('p').textContent;
            storeAnswer(questionId, answerText, isCorrect);
            
            // Auto-advance to next question after 1.5 seconds
            setTimeout(() => {
                if (currentQuestion < totalQuestions - 1) {
                    currentQuestion++;
                    updateQuizDisplay();
                }
            }, 1500);
        }
    });
    
    function updateQuizDisplay() {
        // Hide all questions
        questions.forEach((question, index) => {
            question.classList.toggle('hidden', index !== currentQuestion);
            question.classList.toggle('active', index === currentQuestion);
        });
        
        // Update progress bars
        progressFills.forEach((progressFill, index) => {
            const progress = ((index + 1) / totalQuestions) * 100;
            progressFill.style.width = `${progress}%`;
        });
        
        // Update counters
        counters.forEach((counter, index) => {
            counter.textContent = `${index + 1} / ${totalQuestions}`;
        });
    }
    
    function highlightCorrectAnswer(question) {
        // Find and highlight the correct answer
        const answerBoxes = question.querySelectorAll('.answerBox');
        
        answerBoxes.forEach((box, index) => {
            const isCorrect = box.getAttribute('data-is-correct') === '1';
            
            if (isCorrect) {
                box.classList.add('correct-answer');
                // Force inline styles as a test
                box.style.backgroundColor = '#dcfce7';
                box.style.border = '2px solid #16a34a';
                box.style.boxShadow = '0 0 10px rgba(34, 197, 94, 0.3)';
            }
        });
    }
    
    function storeAnswer(questionId, answerText, isCorrect) {
        // Store in localStorage for persistence
        const answers = JSON.parse(localStorage.getItem('quizAnswers') || '{}');
        answers[questionId] = {
            text: answerText,
            correct: isCorrect,
            timestamp: new Date().toISOString()
        };
        localStorage.setItem('quizAnswers', JSON.stringify(answers));
        
        // You can also send to server here if needed
        // sendAnswerToServer(questionId, answerText, isCorrect);
    }
    
    function getStoredAnswer(questionId) {
        const answers = JSON.parse(localStorage.getItem('quizAnswers') || '{}');
        return answers[questionId];
    }
    
    // Restore previous answers when navigating
    function restoreAnswers() {
        questions.forEach(question => {
            const questionId = question.querySelector('h5').textContent;
            const storedAnswer = getStoredAnswer(questionId);
            
            if (storedAnswer) {
                const answerBoxes = question.querySelectorAll('.answerBox');
                answerBoxes.forEach(box => {
                    const answerText = box.querySelector('p').textContent;
                    if (answerText === storedAnswer) {
                        box.classList.add('selected');
                    }
                });
            }
        });
    }
    
    // Initialize with stored answers
    restoreAnswers();
    
    // Add keyboard navigation
    container.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' && currentQuestion > 0) {
            currentQuestion--;
            updateQuizDisplay();
        } else if (e.key === 'ArrowRight' && currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            updateQuizDisplay();
        }
    });
    
    // Add smooth transitions
    questions.forEach(question => {
        question.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
    });
}
