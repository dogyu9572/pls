@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>정보 입력</h6>
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

            <form action="{{ route('backoffice.boards.store') }}" method="POST">
                @csrf

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="name" class="board-form-label">게시판 이름 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="slug" class="board-form-label">게시판명 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="slug" name="slug" value="{{ old('slug') }}" required>
                            <small class="board-form-text">영문, 숫자, 대시(-), 언더스코어(_)만 사용 가능합니다.</small>
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <label for="description" class="board-form-label">설명</label>
                    <textarea class="board-form-control board-form-textarea" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                </div>

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="skin_id" class="board-form-label">게시판 스킨 <span class="required">*</span></label>
                            <select class="board-form-control" id="skin_id" name="skin_id" required>
                                <option value="">스킨을 선택하세요</option>
                                @foreach ($skins as $skin)
                                    <option value="{{ $skin->id }}" {{ old('skin_id') == $skin->id ? 'selected' : '' }}>
                                        {{ $skin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="list_count" class="board-form-label">페이지당 글 수</label>
                            <input type="number" class="board-form-control" id="list_count" name="list_count" value="{{ old('list_count', default: 10) }}" min="5" max="100">
                        </div>
                    </div>
                </div>

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-4">
                        <div class="board-form-group">
                            <label class="board-form-label">읽기 권한 <span class="required">*</span></label>
                            <div class="board-radio-group">
                                <div class="board-radio-item">
                                    <input type="radio" id="permission_read_all" name="permission_read" value="all" class="board-radio-input" {{ old('permission_read', 'all') == 'all' ? 'checked' : '' }}>
                                    <label for="permission_read_all">모두</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="permission_read_member" name="permission_read" value="member" class="board-radio-input" {{ old('permission_read') == 'member' ? 'checked' : '' }}>
                                    <label for="permission_read_member">회원만</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="permission_read_admin" name="permission_read" value="admin" class="board-radio-input" {{ old('permission_read') == 'admin' ? 'checked' : '' }}>
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
                                    <input type="radio" id="permission_write_all" name="permission_write" value="all" class="board-radio-input" {{ old('permission_write', 'all') == 'all' ? 'checked' : '' }}>
                                    <label for="permission_write_all">모두</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="permission_write_member" name="permission_write" value="member" class="board-radio-input" {{ old('permission_write') == 'member' ? 'checked' : '' }}>
                                    <label for="permission_write_member">회원만</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="permission_write_admin" name="permission_write" value="admin" class="board-radio-input" {{ old('permission_write') == 'admin' ? 'checked' : '' }}>
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
                                    <input type="radio" id="permission_comment_all" name="permission_comment" value="all" class="board-radio-input" {{ old('permission_comment', 'all') == 'all' ? 'checked' : '' }}>
                                    <label for="permission_comment_all">모두</label>
                                </div>
                                <div class="board-radio-item">
                                    <input type="radio" id="permission_comment_member" name="permission_comment" value="member" class="board-radio-input" {{ old('permission_comment') == 'member' ? 'checked' : '' }}>
                                    <label for="permission_comment_member">회원만</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="permission_comment_admin" name="permission_comment" value="admin" class="board-radio-input" {{ old('permission_comment') == 'admin' ? 'checked' : '' }}>
                                    <label for="permission_comment_admin">관리자만</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label for="is_active" class="board-form-label">사용 여부</label>
                    </div>
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="enable_notice" name="enable_notice" value="1" {{ old('enable_notice', '0') == '1' ? 'checked' : '' }}>
                        <label for="enable_notice" class="board-form-label">공지 기능 활성화</label>
                    </div>
                    <small class="board-form-text">체크하면 게시글 작성 시 공지여부를 설정할 수 있습니다. 공지글은 최상단에 표시됩니다.</small>
                </div>

                <!-- 커스텀 필드 설정 -->
                <div class="board-form-group">
                    <label class="board-form-label">커스텀 필드 설정</label>
                    <div class="custom-fields-container">
                        <div class="custom-fields-list" id="customFieldsList">
                            <!-- 기존 커스텀 필드들이 여기에 동적으로 추가됩니다 -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomField()">
                            <i class="fas fa-plus"></i> 커스텀 필드 추가
                        </button>
                    </div>
                    <small class="board-form-text">게시글 작성 시 추가로 입력받을 필드들을 설정할 수 있습니다.</small>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">저장</button>
                    <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">취소</a>                    
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // 게시판 이름 입력 시 자동으로 슬러그 생성
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                if (!slugInput.value || slugInput._autoGenerated) {
                    const slug = this.value
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '') // 영문자, 숫자, 공백, 하이픈만 허용
                        .replace(/\s+/g, '-')     // 공백을 하이픈으로 변환
                        .replace(/-+/g, '-');     // 중복된 하이픈 제거

                    slugInput.value = slug;
                    slugInput._autoGenerated = true;
                }
            });

            slugInput.addEventListener('input', function() {
                slugInput._autoGenerated = false;
            });
        }
    });

    // 커스텀 필드 관리
    let customFieldCounter = 0;

    function addCustomField() {
        const container = document.getElementById('customFieldsList');
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
                            <input type="text" class="board-form-control" name="custom_fields[${customFieldCounter-1}][name]" placeholder="예: location" required>
                            <small class="board-form-text">영문, 소문자, 언더스코어(_) 사용</small>
                        </div>
                    </div>
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label class="board-form-label">라벨 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" name="custom_fields[${customFieldCounter-1}][label]" placeholder="예: 위치" required>
                            <small class="board-form-text">사용자에게 보여질 이름</small>
                        </div>
                    </div>
                </div>
                <div class="board-form-row">
                    <div class="board-form-col board-form-col-4">
                        <div class="board-form-group">
                            <label class="board-form-label">필드 타입 <span class="required">*</span></label>
                            <select class="board-form-control" name="custom_fields[${customFieldCounter-1}][type]" onchange="toggleFieldOptions(this, ${customFieldCounter-1})" required>
                                <option value="">타입 선택</option>
                                <option value="text">텍스트</option>
                                <option value="select">선택</option>
                                <option value="checkbox">체크박스</option>
                                <option value="date">날짜</option>
                            </select>
                        </div>
                    </div>
                    <div class="board-form-col board-form-col-4">
                        <div class="board-form-group">
                            <label class="board-form-label">최대 길이</label>
                            <input type="number" class="board-form-control" name="custom_fields[${customFieldCounter-1}][max_length]" placeholder="예: 50" min="1" max="255">
                        </div>
                    </div>
                    <div class="board-form-col board-form-col-4">
                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" name="custom_fields[${customFieldCounter-1}][required]" value="1">
                                <label class="board-form-label">필수 입력</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="board-form-group field-options" id="field_options_${customFieldCounter-1}" style="display: none;">
                    <label class="board-form-label">선택 옵션</label>
                    <textarea class="board-form-control" name="custom_fields[${customFieldCounter-1}][options]" placeholder="한 줄에 하나씩 입력하세요&#10;예:&#10;일반&#10;프리미엄&#10;VIP" rows="3"></textarea>
                    <small class="board-form-text">select 또는 checkbox 타입일 때 사용됩니다. 한 줄에 하나씩 입력하세요.</small>
                </div>
                <div class="board-form-group">
                    <label class="board-form-label">플레이스홀더</label>
                    <input type="text" class="board-form-control" name="custom_fields[${customFieldCounter-1}][placeholder]" placeholder="예: 위치를 입력하세요">
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
        });
    }

    function toggleFieldOptions(select, index) {
        const optionsDiv = document.getElementById('field_options_' + index);
        if (select.value === 'select' || select.value === 'checkbox') {
            optionsDiv.style.display = 'block';
        } else {
            optionsDiv.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
