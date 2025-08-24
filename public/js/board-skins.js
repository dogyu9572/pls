/**
 * 게시판 스킨 관리 JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // 알림 메시지 자동 닫기
    const alerts = document.querySelectorAll('.alert-dismissible');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                const closeBtn = alert.querySelector('.close');
                if (closeBtn) {
                    closeBtn.click();
                }
            });
        }, 5000);
    }

    // 썸네일 이미지 미리보기 기능
    const thumbnailInput = document.getElementById('thumbnail');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function(event) {
            const previewContainer = document.getElementById('thumbnail-preview-container');
            const previewImg = document.getElementById('thumbnail-preview');

            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                };

                reader.readAsDataURL(event.target.files[0]);
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    }

    // JSON 옵션 입력값 검증
    const optionsField = document.getElementById('options');
    if (optionsField) {
        optionsField.addEventListener('blur', function() {
            try {
                const value = optionsField.value.trim();
                if (value && value !== '{}') {
                    JSON.parse(value);
                    // 유효한 JSON이면 스타일 초기화
                    optionsField.classList.remove('is-invalid');
                }
            } catch (e) {
                // 유효하지 않은 JSON이면 오류 표시
                optionsField.classList.add('is-invalid');

                // 폼 제출 시 유효성 검사를 위한 이벤트 리스너
                const form = optionsField.closest('form');
                if (form) {
                    form.addEventListener('submit', function(event) {
                        try {
                            const value = optionsField.value.trim();
                            if (value && value !== '{}') {
                                JSON.parse(value);
                            }
                        } catch (e) {
                            event.preventDefault();
                            alert('JSON 옵션 형식이 올바르지 않습니다. 유효한 JSON 형식으로 입력해주세요.');
                        }
                    });
                }
            }
        });
    }

    // 템플릿 편집기가 있는 경우 CodeMirror 초기화
    const templateEditor = document.getElementById('template-editor');
    if (templateEditor && window.CodeMirror) {
        const editor = CodeMirror.fromTextArea(templateEditor, {
            lineNumbers: true,
            mode: 'htmlmixed',
            theme: 'monokai',
            indentUnit: 4,
            autoCloseTags: true,
            autoCloseBrackets: true,
            matchBrackets: true,
            styleActiveLine: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete",
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });

        // 저장 버튼 클릭 시 에디터 내용 반영
        const saveBtn = document.querySelector('.template-save-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                editor.save();
            });
        }
    }

    // 삭제 확인 창
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('정말 이 스킨을 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.')) {
                    event.preventDefault();
                }
            });
        });
    }
});
