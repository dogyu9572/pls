@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('content')
<div class="board-container">
    <div class="board-header">      
        <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="board-alert board-alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="board-card">
                <div class="board-card-header">
                    <h6>정보 수정</h6>
                </div>
                <div class="board-card-body">
                    <form action="{{ route('backoffice.boards.update', $board) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="name" class="board-form-label">게시판 이름 <span class="required">*</span></label>
                                    <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name', $board->name) }}" required>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="slug" class="board-form-label">게시판명 <span class="required">*</span></label>
                                    <input type="text" class="board-form-control" id="slug" name="slug" value="{{ old('slug', $board->slug) }}" readonly>
                                    <small class="board-form-text">게시판명은 수정할 수 없습니다.</small>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <label for="description" class="board-form-label">설명</label>
                            <textarea class="board-form-control board-form-textarea" id="description" name="description" rows="2">{{ old('description', $board->description) }}</textarea>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="skin_id" class="board-form-label">게시판 스킨 <span class="required">*</span></label>
                                    <select class="board-form-control" id="skin_id" name="skin_id" required>
                                        <option value="">스킨을 선택하세요</option>
                                        @foreach ($skins as $skin)
                                            <option value="{{ $skin->id }}" {{ old('skin_id', $board->skin_id) == $skin->id ? 'selected' : '' }}>
                                                {{ $skin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="list_count" class="board-form-label">페이지당 글 수</label>
                                    <input type="number" class="board-form-control" id="list_count" name="list_count" value="{{ old('list_count', $board->list_count ?? 15) }}" min="5" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-4">
                                <div class="board-form-group">
                                    <label class="board-form-label">읽기 권한 <span class="required">*</span></label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_read_all" name="permission_read" value="all" class="board-radio-input" {{ old('permission_read', $board->permission_read) == 'all' ? 'checked' : '' }}>
                                            <label for="permission_read_all">모두</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_read_member" name="permission_read" value="member" class="board-radio-input" {{ old('permission_read', $board->permission_read) == 'member' ? 'checked' : '' }}>
                                            <label for="permission_read_member">회원만</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_read_admin" name="permission_read" value="admin" class="board-radio-input" {{ old('permission_read', $board->permission_read) == 'admin' ? 'checked' : '' }}>
                                            <label for="permission_read_admin">관리자만</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-4">
                                <div class="board-form-group">
                                    <label class="board-form-label">쓰기 권한 <span class="required">*</span></label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_write_all" name="permission_write" value="all" class="board-radio-input" {{ old('permission_write', $board->permission_write) == 'all' ? 'checked' : '' }}>
                                            <label for="permission_write_all">모두</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_write_member" name="permission_write" value="member" class="board-radio-input" {{ old('permission_write', $board->permission_write) == 'member' ? 'checked' : '' }}>
                                            <label for="permission_write_member">회원만</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_write_admin" name="permission_write" value="admin" class="board-radio-input" {{ old('permission_write', $board->permission_write) == 'admin' ? 'checked' : '' }}>
                                            <label for="permission_write_admin">관리자만</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-4">
                                <div class="board-form-group">
                                    <label class="board-form-label">댓글 작성 권한 <span class="required">*</span></label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_comment_all" name="permission_comment" value="all" class="board-radio-input" {{ old('permission_comment', $board->permission_comment) == 'all' ? 'checked' : '' }}>
                                            <label for="permission_comment_all">모두</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_comment_member" name="permission_comment" value="member" class="board-radio-input" {{ old('permission_comment', $board->permission_comment) == 'member' ? 'checked' : '' }}>
                                            <label for="permission_comment_member">회원만</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_comment_admin" name="permission_comment" value="admin" class="board-radio-input" {{ old('permission_comment', $board->permission_comment) == 'admin' ? 'checked' : '' }}>
                                            <label for="permission_comment_admin">관리자만</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', $board->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="board-form-label">사용 여부</label>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" id="enable_notice" name="enable_notice" value="1" {{ old('enable_notice', $board->enable_notice ?? '0') == '1' ? 'checked' : '' }}>
                                <label for="enable_notice" class="board-form-label">공지 기능 활성화</label>
                            </div>
                            <small class="board-form-text">체크하면 게시글 작성 시 공지여부를 설정할 수 있습니다. 공지글은 최상단에 표시됩니다.</small>
                        </div>

                        <!-- 커스텀 필드 설정 -->
                        <div class="board-form-group">
                            <label class="board-form-label">커스텀 필드 설정</label>
                            <div class="custom-fields-container">
                                <div class="custom-fields-list" id="customFieldsList">
                                    @if($board->custom_fields_config && count($board->custom_fields_config) > 0)
                                        @foreach($board->custom_fields_config as $index => $fieldConfig)
                                            <div class="custom-field-item" id="custom_field_{{ $index }}">
                                                <div class="custom-field-header">
                                                    <h6>커스텀 필드 #{{ $index + 1 }}</h6>
                                                    <div class="custom-field-actions">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moveCustomField('custom_field_{{ $index }}', 'up')" title="위로 이동">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moveCustomField('custom_field_{{ $index }}', 'down')" title="아래로 이동">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomField('custom_field_{{ $index }}')">
                                                            <i class="fas fa-trash"></i> 삭제
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="board-form-row">
                                                    <div class="board-form-col board-form-col-6">
                                                        <div class="board-form-group">
                                                            <label class="board-form-label">필드명 <span class="required">*</span></label>
                                                            <input type="text" class="board-form-control" name="custom_fields[{{ $index }}][name]" value="{{ $fieldConfig['name'] }}" placeholder="예: location" required>
                                                            <small class="board-form-text">영문, 소문자, 언더스코어(_) 사용</small>
                                                        </div>
                                                    </div>
                                                    <div class="board-form-col board-form-col-6">
                                                        <div class="board-form-group">
                                                            <label class="board-form-label">라벨 <span class="required">*</span></label>
                                                            <input type="text" class="board-form-control" name="custom_fields[{{ $index }}][label]" value="{{ $fieldConfig['label'] }}" placeholder="예: 위치" required>
                                                            <small class="board-form-text">사용자에게 보여질 이름</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="board-form-row">
                                                    <div class="board-form-col board-form-col-4">
                                                        <div class="board-form-group">
                                                            <label class="board-form-label">필드 타입 <span class="required">*</span></label>
                                                            <select class="board-form-control" name="custom_fields[{{ $index }}][type]" onchange="toggleFieldOptions(this, {{ $index }})" required>
                                                                <option value="">타입 선택</option>
                                                                <option value="text" {{ $fieldConfig['type'] == 'text' ? 'selected' : '' }}>텍스트</option>
                                                                <option value="select" {{ $fieldConfig['type'] == 'select' ? 'selected' : '' }}>셀렉박스</option>
                                                                <option value="checkbox" {{ $fieldConfig['type'] == 'checkbox' ? 'selected' : '' }}>체크박스</option>
                                                                <option value="radio" {{ $fieldConfig['type'] == 'radio' ? 'selected' : '' }}>라디오 버튼</option>
                                                                <option value="date" {{ $fieldConfig['type'] == 'date' ? 'selected' : '' }}>날짜</option>
                                                                <option value="editor" {{ $fieldConfig['type'] == 'editor' ? 'selected' : '' }}>에디터</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="board-form-col board-form-col-4">
                                                        <div class="board-form-group">
                                                            <div class="board-checkbox-item">
                                                                <input type="checkbox" class="board-checkbox-input" name="custom_fields[{{ $index }}][required]" value="1" {{ ($fieldConfig['required'] ?? false) ? 'checked' : '' }}>
                                                                <label class="board-form-label">필수 입력</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="board-form-group field-options" id="field_options_{{ $index }}" style="display: {{ $fieldConfig['type'] == 'select' || $fieldConfig['type'] == 'checkbox' ? 'block' : 'none' }};">
                                                    <label class="board-form-label">선택 옵션</label>
                                                    <textarea class="board-form-control" name="custom_fields[{{ $index }}][options]" placeholder="한 줄에 하나씩 입력하세요&#10;예:&#10;일반&#10;프리미엄&#10;VIP" rows="3">{{ $fieldConfig['options'] ?? '' }}</textarea>
                                                    <small class="board-form-text">select 또는 checkbox 타입일 때 사용됩니다. 한 줄에 하나씩 입력하세요.</small>
                                                </div>
                                                <div class="board-form-group">
                                                    <label class="board-form-label">플레이스홀더</label>
                                                    <input type="text" class="board-form-control" name="custom_fields[{{ $index }}][placeholder]" value="{{ $fieldConfig['placeholder'] ?? '' }}" placeholder="예: 위치를 입력하세요">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addCustomFieldBtn">
                                    <i class="fas fa-plus"></i> 커스텀 필드 추가
                                </button>
                            </div>
                            <small class="board-form-text">게시글 작성 시 추가로 입력받을 필드들을 설정할 수 있습니다.</small>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label class="board-form-label">생성일</label>
                                    <div class="board-form-text">{{ $board->created_at->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label class="board-form-label">마지막 수정일</label>
                                    <div class="board-form-text">{{ $board->updated_at->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-actions">
                            <button type="submit" class="btn btn-primary">수정</button>
                            <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">취소</a>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        // 이미 값이 있는 경우 자동 생성 비활성화
        if (nameInput && slugInput && !slugInput.value) {
            nameInput.addEventListener('input', function() {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                slugInput.value = slug;
            });
        }

        // 커스텀 필드 추가 버튼 이벤트 리스너
        const addCustomFieldBtn = document.getElementById('addCustomFieldBtn');
        if (addCustomFieldBtn) {
            addCustomFieldBtn.addEventListener('click', addCustomField);
        }
    });

    // 커스텀 필드 관리
    let customFieldCounter = {{ $board->custom_fields_config ? count($board->custom_fields_config) : 0 }};

    function addCustomField() {
        const container = document.getElementById('customFieldsList');
        
        if (!container) {
            console.error('customFieldsList를 찾을 수 없습니다!');
            return;
        }
        
        const existingFields = container.children.length;
        
        const fieldId = 'custom_field_' + customFieldCounter++;
        
        const fieldHtml = `
            <div class="custom-field-item" id="${fieldId}">
                <div class="custom-field-header">
                    <h6>커스텀 필드 #${customFieldCounter}</h6>
                    <div class="custom-field-actions">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moveCustomField('${fieldId}', 'up')" title="위로 이동">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moveCustomField('${fieldId}', 'down')" title="아래로 이동">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomField('${fieldId}')">
                            <i class="fas fa-trash"></i> 삭제
                        </button>
                    </div>
                </div>
                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label class="board-form-label">필드명 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" name="custom_fields[${existingFields}][name]" placeholder="예: location" required>
                            <small class="board-form-text">영문, 소문자, 언더스코어(_) 사용</small>
                        </div>
                    </div>
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label class="board-form-label">라벨 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" name="custom_fields[${existingFields}][label]" placeholder="예: 위치" required>
                            <small class="board-form-text">사용자에게 보여질 이름</small>
                        </div>
                    </div>
                </div>
                <div class="board-form-row">
                    <div class="board-form-col board-form-col-4">
                        <div class="board-form-group">
                            <label class="board-form-label">필드 타입 <span class="required">*</span></label>
                            <select class="board-form-control" name="custom_fields[${existingFields}][type]" onchange="toggleFieldOptions(this, ${existingFields})" required>
                                <option value="">타입 선택</option>
                                <option value="text">텍스트</option>
                                <option value="select">셀렉박스</option>
                                <option value="checkbox">체크박스</option>
                                <option value="radio">라디오 버튼</option>
                                <option value="editor">에디터</option>
                                <option value="date">날짜</option>                                
                            </select>
                        </div>
                    </div>
                    <div class="board-form-col board-form-col-4">
                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" name="custom_fields[${existingFields}][required]" value="1">
                                <label class="board-form-label">필수 입력</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="board-form-group field-options" id="field_options_${existingFields}" style="display: none;">
                    <label class="board-form-label">선택 옵션</label>
                    <textarea class="board-form-control" name="custom_fields[${existingFields}][options]" placeholder="한 줄에 하나씩 입력하세요&#10;예:&#10;일반&#10;프리미엄&#10;VIP" rows="3"></textarea>
                <small class="board-form-text">select 또는 checkbox 타입일 때 사용됩니다. 한 줄에 하나씩 입력하세요.</small>
                </div>
                <div class="board-form-group">
                    <label class="board-form-label">플레이스홀더</label>
                    <input type="text" class="board-form-control" name="custom_fields[${existingFields}][placeholder]" placeholder="예: 위치를 입력하세요">
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', fieldHtml);
    }

    function removeCustomField(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.remove();
        }
    }

    function moveCustomField(fieldId, direction) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        const container = document.getElementById('customFieldsList');
        const fields = Array.from(container.children);
        const currentIndex = fields.indexOf(field);
        
        if (direction === 'up' && currentIndex > 0) {
            // 위로 이동
            container.insertBefore(field, fields[currentIndex - 1]);
        } else if (direction === 'down' && currentIndex < fields.length - 1) {
            // 아래로 이동
            container.insertBefore(field, fields[currentIndex + 1].nextSibling);
        }
        
        // 순서 번호 업데이트
        updateFieldNumbers();
    }
    
    function updateFieldNumbers() {
        const fields = document.querySelectorAll('.custom-field-item');
        fields.forEach((field, index) => {
            const header = field.querySelector('h6');
            if (header) {
                header.textContent = `커스텀 필드 #${index + 1}`;
            }
            
            // 배열 인덱스 업데이트
            updateFieldIndexes(field, index);
        });
    }
    
    function updateFieldIndexes(field, newIndex) {
        // name 속성의 배열 인덱스 업데이트
        const inputs = field.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name && input.name.includes('custom_fields[')) {
                input.name = input.name.replace(/custom_fields\[\d+\]/, `custom_fields[${newIndex}]`);
            }
        });
        
        // onchange 이벤트의 인덱스 업데이트
        const typeSelect = field.querySelector('select[name*="[type]"]');
        if (typeSelect) {
            typeSelect.setAttribute('onchange', `toggleFieldOptions(this, ${newIndex})`);
        }
        
        // field_options ID 업데이트
        const optionsDiv = field.querySelector('.field-options');
        if (optionsDiv) {
            optionsDiv.id = `field_options_${newIndex}`;
        }
    }

@endpush
