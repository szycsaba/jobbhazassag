@extends('article-layouts.app')
@section('title', $article->title)

@section('header')
@if($header)
<div class="bg-[#f6c343] w-full">
    <section class="flex flex-col justify-start items-center h-screen box-border bg-[linear-gradient(rgba(0,0,0,0.1),rgba(0,0,0,0.3))]">
            <h1 class="h-[25%] md:h-[30%] !text-white text-center !font-['Bona_Nova',serif] !font-[700] !text-[3rem] md:!text-[5.2rem] flex items-center justify-center w-full max-w-[901px] mt-0 mb-0 leading-[1.2]">{{ $header->title }}</h1>
            <div class="flex justify-center items-center h-[50%] md:h-[45%]">
                <div class="w-full h-full max-w-[901px]">
                    @if($header->image_url)
                        <img src="{{ $header->image_url }}" alt="Background" class="w-full h-full object-contain object-center">
                    @endif
                </div>
            </div>
            <h5 class="h-[25%] md:h-[25%] !text-white text-center max-w-[901px] mx-auto mt-[20px] !font-['Noto_Sans',sans-serif] !font-medium self-start px-[10px] w-full !text-[2rem] leading-[38px]">{{ $header->subtitle }}</h5>
    </section>
</div>
@endif
@endsection

@section('content')
    @foreach($article->articleBlocks as $articleBlock)
        @php
            $typeAttributes = $articleBlock->type->articleTypeAttributes;
            $backgroundColor = $typeAttributes ? $typeAttributes->background : '';
            $textColor = $typeAttributes ? $typeAttributes->text : '';
            $style = '';
            if ($backgroundColor) {
                $style .= 'background-color: ' . $backgroundColor . ' !important; ';
            }
            if ($textColor) {
                $style .= 'color: ' . $textColor . ' !important; ';
            }
        @endphp

        @if($articleBlock->type->name === 'title')
            <h1 class="title text-center font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out" @if($style) style="{{ $style }}" @endif>@markup($articleBlock->content)</h1>
        @elseif($articleBlock->type->name === 'subtitle')
            <h5 class="subtitle text-center font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mt-8 mb-8" @if($style) style="{{ $style }}" @endif>@markup($articleBlock->content)</h5>
        @elseif($articleBlock->type->name === 'left-subtitle')
            <h5 class="subtitle font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out text-center mb-[1rem]" @if($style) style="{{ $style }}" @endif>@markup($articleBlock->content)</h5>
        @elseif($articleBlock->type->name === 'yellow')
            <div class="transpBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out font-medium mt-4 mb-4" @if($style) style="{{ $style }}" @endif>
                @markup($articleBlock->content)
            </div>
        @elseif($articleBlock->type->name === 'white')
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out font-medium mt-4 mb-4" @if($style) style="{{ $style }}" @endif>
                @markup($articleBlock->content)
            </div>
        @elseif($articleBlock->type->name === 'green')
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out font-medium mt-4 mb-4 bg-[linear-gradient(135deg,var(--greenBox-bg)_0%,var(--light-teal_bg)_100%)]" @if($style) style="{{ $style }}" @endif>
                @markup($articleBlock->content)
            </div>
        @elseif($articleBlock->type->name === 'image')
            <div class="boxHolder flex justify-center fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out" @if($style) style="{{ $style }}" @endif>
                <div class="imgBox w-full max-w-[400px]">
                    <img class="image w-full h-auto" src="{{ $articleBlock->content }}" alt="">
                </div>
            </div>
        @elseif($articleBlock->type->name === 'columns')
            <div class="solutionBox flex flex-col md:flex-row gap-2 fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mt-4 mb-4 md:h-[500px] lg:h-[520px]" @if($style) style="{{ $style }}" @endif>
                @markup($articleBlock->content)
            </div>
        @elseif($articleBlock->type->name === 'big-title')
            <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out text-center mt-[1rem] mb-[1rem]" @if($style) style="{{ $style }}" @endif>@markup($articleBlock->content)</h5>
        @elseif($articleBlock->type->name === 'column-title')
            <h5 class="font-bold pt-4 text-center" @if($style) style="{{ $style }}" @endif>@markup($articleBlock->content)</h5>
        @elseif($articleBlock->type->name === 'self-awareness')
        <div class="mini-reflection fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out bg-[var(--light-butter-g)] p-2 sm:p-8 mt-4 mb-4" id="selfLearn">
            <h5 class="articleSubtitle text-center! font-extrabold text-left mb-4" @if($style) style="{{ $style }}" @endif>Mini önismeret</h5>
            <p class="text-l text-center text-[#326e6c] font-medium mb-6"><i>Az itt található kérdések nem terápiát helyettesítenek, hanem egyszerű önismereti gyakorlatok. A válaszaidat el is mentheted, és ha később visszalátogatsz a leckére, újra elolvashatod őket.</i>
            </p>
                <div class="dropdownMenu w-full sm:max-w-[850px] m-auto">
                    @foreach($reflectionQuestions as $reflectionId => $questions)
                        @foreach($questions as $question)
                            <div class="option w-full mb-10 origin-right scale-x-100 transition-transform duration-300 hover:scale-x-98">
                                <button class="flex w-full justify-between items-center dropdownButton bg-[var(--butterYellow-bg)] p-4 text-[1.2rem] text-left focus:outline-none focus:shadow-[0_0_10px_2px_rgba(0,128,0,0.7)] font-medium cursor-pointer rounded-tl-[10px] rounded-tr-[10px]">
                                    <span class="fontSans">{{ $question->description }}</span>
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" class="arrowIcon min-w-[16px] h-5 sm:min-w-[20px] sm:min-h-[20px] text-grey-500 transform transition-transform duration-300 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="arrowIcon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg> -->
                                </button>
                                <div id="dropdownInputWrapper" class="">
                                     <textarea  class="inpArea bg-[var(--butterYellow-bg)] w-full p-4 transition focus:outline-none focus:outline-none cursor-pointer cursor-text resize-none rounded-bl-[10px] rounded-br-[10px]" 
                                            rows="2"
                                            placeholder="Gépelhetsz ide..."
                                            data-reflection-id="{{ $reflectionId }}"
                                            data-question-id="{{ $question->id }}">{{ isset($userReflectionNotes[$question->id]) ? $userReflectionNotes[$question->id] : '' }}</textarea>
                                </div>
                            </div>
                           
                        @endforeach
                    @endforeach
                    <div class="flex justify-center">
                        <button class="btn-mini-ref bg-[#587a7b] text-white font-bold cursor-pointer px-12 py-4 rounded-[15px] hover:scale-105 transition transform shadow-[0_4px_12px_rgba(0,0,0,0.08)] hover:bg-[#328d8d] hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)]">Mentés</button>
                    </div>
                </div>
        
        </div>
        @elseif($articleBlock->type->name === 'quiz')
            @php
                $quizId = is_numeric($articleBlock->content) ? (int)$articleBlock->content : null;
            @endphp
            @if($quizId && isset($quizQuestions[$quizId]) && $quizQuestions[$quizId]->isNotEmpty())
                <div class="quizBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mt-4 mb-4" id="quiz" @if($style) style="{{ $style }}" @endif>
                    @foreach($quizQuestions[$quizId] as $index => $question)
                        <div class="quiz-question {{ $index === 0 ? 'active' : 'hidden' }}" data-question="{{ $index }}">
                            <h5 class="articleSubtitle font-extrabold mb-6 text-center">Kvíz</h5>
                            <p class="text-l text-center text-[#326e6c] font-semibold mb-6"><i>Rövid kvíz, hogy megnézd, mit vittél magaddal a leckéből. Segít, hogy a tudás biztosan megmaradjon.</i></p>
                            <h5 class="subtitle font-extrabold mb-6 text-center !text-[1.5rem] md:!text-[2rem] !leading-[25px]">{{ $question->question_text }}</h5>
                            
                            @foreach($question->options as $optionIndex => $option)
                                <div class="answerBox mb-4 font-medium bg-white border-2 border-[#f5f1eb] w-full origin-right scale-x-100 transition-transform duration-300 hover:scale-x-98 text-[1.2rem]" 
                                     data-option-id="{{ $option->id }}" 
                                     data-is-correct="{{ $option->is_correct ? 1 : 0 }}"
                                     style="cursor: pointer !important;">
                                    <p class="font-medium">{{ $option->option_text }}</p>
                                </div>
                            @endforeach
                            
                            <div class="w-full rounded-full h-[8px] mt-12 mb-6 flex justify-center">
                                <div class="progress-bar-quiz w-[300px] h-[8px] bg-white">
                                    <div class="bg-[linear-gradient(90deg,#326e6c,#e8946f)] h-[8px] rounded-full transition-all duration-500 ease-in-out quiz-progress-fill" style="width: {{ (($index + 1) / count($quizQuestions[$quizId])) * 100 }}%;"></div>
                                </div>
                            </div>
                            <div class="flex justify-center mt-4 mb-4">
                                    <span class="text-lg font-medium text-gray-700 quiz-counter">{{ $index + 1 }} / {{ count($quizQuestions[$quizId]) }}</span>
                                </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    @endforeach

    @if($previousLessons->isNotEmpty())
        <div id="previousArticles" class="previous-lessons fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mt-8 mb-4">
            <h5 class="articleSubtitle font-extrabold text-center mb-6">Korábbi leckék</h5>
            <div class="whiteBox">
                <ul class="space-y-3">
                    @foreach($previousLessons as $index => $lesson)
                        <li class="flex items-center">
                            <span class="flex-shrink-0 w-8 h-8 bg-[#587a7b] text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                {{ $index }}
                            </span>
                            <a href="{{ $lesson->url }}" 
                               class="text-[#326e6c] hover:text-[#587a7b] font-medium transition-colors duration-200 flex-1">
                                {{ $lesson->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
    .quizBox .answerBox {
        cursor: pointer !important;
        transition: all 0.3s ease;
        padding: 12px 16px !important;
        border: 2px solid transparent !important;
        border-radius: 8px !important;
        margin-bottom: 12px !important;
        background-color: #ffffff !important;
    }
    
    .quizBox .answerBox:hover {
        cursor: pointer !important;
        background-color: #fef3c7 !important;
        transform: translateY(-1px);
        border-color: #f59e0b !important;
    }
    
    .quizBox .answerBox * {
        cursor: pointer !important;
    }
    
    .quizBox .answerBox:hover * {
        cursor: pointer !important;
    }
    
    /* More specific selectors to override any conflicts */
    div.quizBox div.answerBox {
        cursor: pointer !important;
    }
    
    div.quizBox div.answerBox:hover {
        cursor: pointer !important;
    }
    
    div.quizBox div.answerBox p {
        cursor: pointer !important;
    }
    
    div.quizBox div.answerBox:hover p {
        cursor: pointer !important;
    }
    
    .quizBox .answerBox.selected {
        background-color: #dbeafe !important;
        border: 2px solid #3b82f6 !important;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.3) !important;
    }
    
    .quizBox .answerBox.correct-answer {
        background-color: #dcfce7 !important;
        border: 2px solid #16a34a !important;
        position: relative !important;
        box-shadow: 0 0 10px rgba(34, 197, 94, 0.3) !important;
    }
    
    .quizBox .answerBox.correct-answer::after {
        content: "✓" !important;
        position: absolute !important;
        right: 10px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #16a34a !important;
        font-weight: bold !important;
        font-size: 1.2em !important;
    }
    
    .quizBox .answerBox.incorrect {
        background-color: #fecaca !important;
        border: 2px solid #dc2626 !important;
        position: relative !important;
        box-shadow: 0 0 10px rgba(220, 38, 38, 0.3) !important;
    }
    
    .quizBox .answerBox.incorrect::after {
        content: "✗" !important;
        position: absolute !important;
        right: 10px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #dc2626 !important;
        font-weight: bold !important;
        font-size: 1.2em !important;
    }
</style>
@endpush
