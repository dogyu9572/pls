@extends('backoffice.layouts.app')

@section('title', ($board->name ?? '게시판'))

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
            <h6>게시글 작성</h6>
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

            <form action="{{ route('backoffice.board-posts.store', $board->slug ?? 'notice') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($board->isNoticeEnabled())
                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" 
                               class="board-checkbox-input" 
                               id="is_notice" 
                               name="is_notice" 
                               value="1" 
                               {{ old('is_notice') == '1' ? 'checked' : '' }}>
                        <label for="is_notice" class="board-form-label">
                            <i class="fas fa-bullhorn"></i> 공지글
                        </label>
                    </div>
                    <small class="board-form-text">체크하면 공지글로 설정되어 최상단에 표시됩니다.</small>
                </div>
                @endif

                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="category" value="일반">
                </div>

                <div class="board-form-group">
                    <label for="title" class="board-form-label">협력사명 <span class="required">*</span></label>
                    <input type="text" class="board-form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="content" value="내용">
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
                        <div class="board-file-preview" id="thumbnailPreview"></div>
                    </div>
                </div>

                @if($board->enable_sorting)
                <div class="board-form-group">
                    <label for="sort_order" class="board-form-label">정렬 순서</label>
                    <input type="number" class="board-form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                    <small class="board-form-text">숫자가 작을수록 위에 표시됩니다. (0이면 자동 정렬)</small>
                </div>
                @endif

                <!-- 커스텀 필드 입력 폼 -->
                @if($board->custom_fields_config && count($board->custom_fields_config) > 0)
                    @foreach($board->custom_fields_config as $fieldConfig)
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
                                       value="{{ old('custom_field_' . $fieldConfig['name']) }}"
                                       placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                       @if($fieldConfig['required']) required @endif>
                            @elseif($fieldConfig['type'] === 'select')
                                @if($fieldConfig['options'])
                                    <select class="board-form-control" 
                                            id="custom_field_{{ $fieldConfig['name'] }}" 
                                            name="custom_field_{{ $fieldConfig['name'] }}"
                                            @if($fieldConfig['required']) required @endif>
                                        <option value="">선택하세요</option>
                                        @foreach(explode("\n", $fieldConfig['options']) as $option)
                                            @php $option = trim($option); @endphp
                                            @if(!empty($option))
                                                <option value="{{ $option }}" {{ old('custom_field_' . $fieldConfig['name']) == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <div class="board-form-text text-muted">셀렉박스는 선택 옵션이 필요합니다.</div>
                                @endif
                            @elseif($fieldConfig['type'] === 'checkbox')
                                @if($fieldConfig['options'])
                                    <div class="board-options-list board-options-horizontal">
                                        @foreach(explode("\n", $fieldConfig['options']) as $option)
                                            @php $option = trim($option); @endphp
                                            @if(!empty($option))
                                                <div class="board-option-item">
                                                    <input type="checkbox" 
                                                           id="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}" 
                                                           name="custom_field_{{ $fieldConfig['name'] }}[]" 
                                                           value="{{ $option }}"
                                                           {{ in_array($option, old('custom_field_' . $fieldConfig['name'], [])) ? 'checked' : '' }}>
                                                    <label for="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}">{{ $option }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="board-checkbox-item">
                                        <input type="checkbox" 
                                               class="board-checkbox-input" 
                                               id="custom_field_{{ $fieldConfig['name'] }}" 
                                               name="custom_field_{{ $fieldConfig['name'] }}" 
                                               value="1"
                                               {{ old('custom_field_' . $fieldConfig['name']) == '1' ? 'checked' : '' }}>
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
                                                <div class="board-option-item">
                                                    <input type="radio" 
                                                           id="option_{{ $fieldConfig['name'] }}_{{ $loop->index }}" 
                                                           name="custom_field_{{ $fieldConfig['name'] }}" 
                                                           value="{{ $option }}"
                                                           {{ old('custom_field_' . $fieldConfig['name']) == $option ? 'checked' : '' }}
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
                                       value="{{ old('custom_field_' . $fieldConfig['name']) }}"
                                       @if($fieldConfig['required']) required @endif>
                            @elseif($fieldConfig['type'] === 'editor')
                                <textarea class="board-form-control board-form-textarea summernote-editor" 
                                          id="custom_field_{{ $fieldConfig['name'] }}" 
                                          name="custom_field_{{ $fieldConfig['name'] }}" 
                                          rows="10"
                                          @if($fieldConfig['required']) required @endif>{{ old('custom_field_' . $fieldConfig['name']) }}</textarea>
                            @endif
                            
                            @if($fieldConfig['max_length'] && in_array($fieldConfig['type'], ['text']))
                                <small class="board-form-text">최대 {{ $fieldConfig['max_length'] }}자 (영어 기준)까지 입력 가능합니다.</small>
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