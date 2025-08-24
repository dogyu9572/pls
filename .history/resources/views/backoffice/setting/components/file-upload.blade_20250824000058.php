@props([
    'name',
    'label',
    'accept' => 'image/*',
    'currentFile' => null,
    'currentFilePath' => null,
    'uploadAreaId' => null,
    'selectedId' => null,
    'fileNameId' => null,
    'removeFunction' => null,
    'previewTitle' => '현재 파일',
    'uploadTitle' => '클릭하여 파일 선택',
    'uploadSubtitle' => '또는 파일을 여기로 드래그하세요'
])

<div class="board-form-group">
    <label for="{{ $name }}" class="board-form-label">{{ $label }}</label>
    <div class="file-upload-container">
        <div class="file-upload-area" id="{{ $uploadAreaId }}">
            <div class="file-upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="file-upload-text">
                <span class="file-upload-title">{{ $uploadTitle }}</span>
                <span class="file-upload-subtitle">{{ $uploadSubtitle }}</span>
            </div>
            <input type="file" class="file-input @error($name) is-invalid @enderror"
                   id="{{ $name }}" name="{{ $name }}" accept="{{ $accept }}">
        </div>
        
        <!-- 파일 선택 완료 후 표시 -->
        <div class="file-selected" id="{{ $selectedId }}">
            <div class="file-selected-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="file-selected-name" id="{{ $fileNameId }}"></div>
            <button type="button" class="file-selected-remove" onclick="removeSelectedFile('{{ $name }}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        @error($name)
            <div class="file-error">{{ $message }}</div>
        @enderror
        
        @if($currentFilePath)
            <div class="file-preview">
                <div class="file-preview-header">
                    <span class="file-preview-title">{{ $previewTitle }}</span>
                    <button type="button" class="file-preview-remove" onclick="{{ $removeFunction }}()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="file-preview-content">
                    @if(str_contains($accept, 'image'))
                        <img src="{{ $currentFilePath }}" alt="{{ $label }}" class="file-preview-image">
                    @else
                        <div class="file-preview-file">
                            <i class="fas fa-file"></i>
                            <span>{{ basename($currentFilePath) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
