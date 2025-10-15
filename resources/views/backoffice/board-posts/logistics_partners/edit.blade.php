@extends('backoffice.layouts.app')

@section('title', $board->name ?? '게시판')

@section('styles')
     <link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
    <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'notice') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>게시글 수정</h6>
        </div>
        <div class="board-card-body">
            @if ($errors->any())
                <div class="board-alert board-alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('backoffice.board-posts.update', [$board->slug ?? 'notice', $post->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($board->isNoticeEnabled())
                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_notice" name="is_notice" value="1" {{ $post->is_notice ? 'checked' : '' }}>
                        <label for="is_notice" class="board-form-label">공지 등록</label>
                    </div>                    
                </div>
                @endif

                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="category" value="{{ $post->category ?? '일반' }}">
                </div>

                <div class="board-form-group">
                    <label for="title" class="board-form-label">협력사명 <span class="required">*</span></label>
                    <input type="text" class="board-form-control" id="title" name="title" value="{{ $post->title }}" required>
                </div>

                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="content" value="{{ $post->content ?? '내용' }}">
                </div>

                <div class="board-form-group">
                    <label for="thumbnail" class="board-form-label">협력사로고</label>
                    <div class="board-file-upload">
                        <div class="board-file-input-wrapper">
                            <input type="file" class="board-file-input" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.gif">
                            <div class="board-file-input-content">
                                <i class="fas fa-image"></i>
                                <span class="board-file-input-text">썸네일 이미지를 선택하거나 여기로 드래그하세요</span>
                                <span class="board-file-input-subtext">JPG, PNG, GIF 파일만 가능 (최대 5MB)</span>
                            </div>
                        </div>
                        @if($post->thumbnail)
                            <input type="hidden" name="existing_thumbnail" value="{{ $post->thumbnail }}">
                            <div class="board-file-preview" id="thumbnailPreview">
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="현재 썸네일" class="thumbnail-preview">
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeThumbnail()">
                                    <i class="fas fa-trash"></i> 썸네일 제거
                                </button>
                            </div>
                        @else
                            <div class="board-file-preview" id="thumbnailPreview"></div>
                        @endif
                    </div>
                </div>

                @if($board->enable_sorting)
                <div class="board-form-group">
                    <label for="sort_order" class="board-form-label">정렬 순서</label>
                    <input type="number" class="board-form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $post->sort_order ?? 0) }}" min="0">
                    <small class="board-form-text">숫자가 클수록 위에 표시됩니다. (0이면 자동으로 최상단에 등록)</small>
                </div>
                @endif

                <!-- 커스텀 필드 입력 폼 -->
                @if($board->custom_fields_config && count($board->custom_fields_config) > 0)
                    @foreach($board->custom_fields_config as $fieldConfig)
                        @php
                            $customFields = $post->custom_fields ? json_decode($post->custom_fields, true) : [];
                            $fieldValue = $customFields[$fieldConfig['name']] ?? '';
                            
                            // 체크박스와 라디오 버튼의 경우 배열로 처리
                            if (in_array($fieldConfig['type'], ['checkbox', 'radio']) && $fieldValue && !is_array($fieldValue)) {
                                $fieldValue = [$fieldValue];
                            }
                        @endphp
                        <div class="board-form-group">
                            <label for="custom_field_{{ $fieldConfig['name'] }}" class="board-form-label">
                                {{ $fieldConfig['label'] }}
                                @if($fieldConfig['required'])
                                    <span class="required">*</span>
                                @endif
                            </label>
                            
                            @if($fieldConfig['type'] === 'text')
                                <input type="text" 
                                       class="board-form-control" 
                                       id="custom_field_{{ $fieldConfig['name'] }}" 
                                       name="custom_field_{{ $fieldConfig['name'] }}" 
                                       value="{{ old('custom_field_' . $fieldConfig['name'], $fieldValue) }}"
                                       placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                       @if($fieldConfig['required']) required @endif>
                            @elseif($fieldConfig['type'] === 'select')
                                <select class="board-form-control" 
                                        id="custom_field_{{ $fieldConfig['name'] }}" 
                                        name="custom_field_{{ $fieldConfig['name'] }}"
                                        @if($fieldConfig['required']) required @endif>
                                    <option value="">선택하세요</option>
                                    @if($fieldConfig['options'])
                                        @foreach(explode("\n", $fieldConfig['options']) as $option)
                                            @php $option = trim($option); @endphp
                                            @if(!empty($option))
                                                <option value="{{ $option }}" {{ old('custom_field_' . $fieldConfig['name'], $fieldValue) == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($fieldConfig['type'] === 'checkbox')
                                @if($fieldConfig['options'])
                                    <div class="board-options-list board-options-horizontal">
                                        @foreach(explode("\n", $fieldConfig['options']) as $option)
                                            @php $option = trim($option); @endphp
                                            @if(!empty($option))
                                                @php
                                                    $isChecked = is_array($fieldValue) ? in_array($option, $fieldValue) : $fieldValue == $option;
                                                    $isOldChecked = is_array(old('custom_field_' . $fieldConfig['name'])) ? in_array($option, old('custom_field_' . $fieldConfig['name'])) : old('custom_field_' . $fieldConfig['name']) == $option;
                                                @endphp
                                                <div class="board-option-item">
                                                    <input type="checkbox" 
                                                           id="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}" 
                                                           name="custom_field_{{ $fieldConfig['name'] }}[]" 
                                                           value="{{ $option }}"
                                                           {{ ($isChecked || $isOldChecked) ? 'checked' : '' }}>
                                                    <label for="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}">{{ $option }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    @php
                                        $isChecked = is_array($fieldValue) ? in_array('1', $fieldValue) : $fieldValue == '1' || $fieldValue == 1;
                                        $isOldChecked = is_array(old('custom_field_' . $fieldConfig['name'])) ? in_array('1', old('custom_field_' . $fieldConfig['name'])) : old('custom_field_' . $fieldConfig['name']) == '1';
                                    @endphp
                                    <div class="board-checkbox-item">
                                        <input type="checkbox" 
                                               class="board-checkbox-input" 
                                               id="custom_field_{{ $fieldConfig['name'] }}" 
                                               name="custom_field_{{ $fieldConfig['name'] }}" 
                                               value="1"
                                               {{ ($isChecked || $isOldChecked) ? 'checked' : '' }}
                                               @if($fieldConfig['required']) required @endif>
                                        <label for="custom_field_{{ $fieldConfig['name'] }}" class="board-form-label">
                                            {{ $fieldConfig['label'] }}
                                        </label>
                                    </div>
                                @endif
                            @elseif($fieldConfig['type'] === 'radio')
                                @if($fieldConfig['options'])
                                    <div class="board-options-list board-options-horizontal">
                                        @foreach(explode("\n", $fieldConfig['options']) as $option)
                                            @php $option = trim($option); @endphp
                                            @if(!empty($option))
                                                @php
                                                    $isChecked = is_array($fieldValue) ? in_array($option, $fieldValue) : $fieldValue == $option;
                                                    $isOldChecked = is_array(old('custom_field_' . $fieldConfig['name'])) ? in_array($option, old('custom_field_' . $fieldConfig['name'])) : old('custom_field_' . $fieldConfig['name']) == $option;
                                                @endphp
                                                <div class="board-option-item">
                                                    <input type="radio" 
                                                           id="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}" 
                                                           name="custom_field_{{ $fieldConfig['name'] }}" 
                                                           value="{{ $option }}"
                                                           {{ ($isChecked || $isOldChecked) ? 'checked' : '' }}
                                                           @if($fieldConfig['required']) required @endif>
                                                    <label for="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}">{{ $option }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="board-form-text text-muted">라디오 버튼은 선택 옵션이 필요합니다.</div>
                                @endif
                            @elseif($fieldConfig['type'] === 'date')
                                <input type="date" 
                                       class="board-form-control" 
                                       id="custom_field_{{ $fieldConfig['name'] }}" 
                                       name="custom_field_{{ $fieldConfig['name'] }}" 
                                       value="{{ old('custom_field_' . $fieldConfig['name'], $fieldValue) }}"
                                       @if($fieldConfig['required']) required @endif>
                            @elseif($fieldConfig['type'] === 'editor')
                                <textarea class="board-form-control board-form-textarea summernote-editor" 
                                          id="custom_field_{{ $fieldConfig['name'] }}" 
                                          name="custom_field_{{ $fieldConfig['name'] }}" 
                                          rows="10"
                                          @if($fieldConfig['required']) required @endif>{{ old('custom_field_' . $fieldConfig['name'], $fieldValue) }}</textarea>
                            @endif
                            
                        </div>
                    @endforeach
                @endif



                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'notice') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- jQuery, Bootstrap, Summernote JS (순서 중요!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/backoffice/board-post-form.js') }}"></script>
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endsection