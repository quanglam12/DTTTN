const fileIcon = {
    'pdf': '../img/icons/pdf.png',
    'doc': '../img/icons/doc.png',
    'docx': '../img/icons/doc.png',
    'png': '../img/icons/image.png',
    'jpg': '../img/icons/image.png',
    'ppt': '../img/icons/powerpoint.png',
    'pptx': '../img/icons/powerpoint.png',
    'xls': '../img/icons/excel.png',
    'xlsx': '../img/icons/excel.png',
    'mp4': '../img/icons/film.png',
};

function renderContent(data) {
    const contentDiv = document.getElementById('chitietbaiviet');

    // Kiểm tra xem contentDiv có tồn tại không
    if (!contentDiv) {
        console.error('Không tìm thấy phần tử #chitietbaiviet');
        return;
    }

    // Kiểm tra dữ liệu đầu vào
    if (!data || !data.blocks || !Array.isArray(data.blocks)) {
        console.error('Dữ liệu không hợp lệ:', data);
        return;
    }

    data.blocks.forEach((block, index) => {
        let htmlElement = '';

        try {
            switch (block.type) {
                case 'header':
                    htmlElement = `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
                    break;
                case 'paragraph':
                    htmlElement = `<p>${block.data.text}</p>`;
                    break;
                case 'image':
                    htmlElement = `<img src="${block.data.file.url}" alt="${block.data.caption}"><p style="text-align: center; font-style: italic;">${block.data.caption}</p>`;
                    break;
                case 'list':
                    const listTag = block.data.style === 'unordered' ? 'ul' : 'ol';
                    htmlElement = `<${listTag}>${block.data.items.map(item => `<li>${item.content}</li>`).join('')}</${listTag}>`;
                    break;
                case 'quote':
                    htmlElement = `<blockquote>${block.data.text}<br><i>${block.data.caption}</i></blockquote>`;
                    break;
                case 'code':
                    htmlElement = `<pre><code>${block.data.code}</code></pre>`;
                    break;
                case 'table':
                    htmlElement = `<table>${block.data.content.map(row => `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`).join('')}</table>`;
                    break;
                case 'warning':
                    htmlElement = `<div class="warning"><strong>${block.data.title}</strong><p>${block.data.message}</p></div>`;
                    break;
                case 'delimiter':
                    htmlElement = `<div class="delimiter">* * *</div>`;
                    break;
                case 'attaches':
                    // Kiểm tra dữ liệu file
                    if (!block.data.file || !block.data.file.size || !block.data.file.url || !block.data.file.extension) {
                        console.error(`Dữ liệu file không hợp lệ tại block ${index}:`, block.data);
                        htmlElement = `<p>[Lỗi: Tệp không hợp lệ]</p>`;
                        break;
                    }

                    const fileSize = block.data.file.size;
                    const sizeFormatted = fileSize > 1024 * 1024
                        ? (fileSize / (1024 * 1024)).toFixed(2) + ' MB'
                        : (fileSize / 1024).toFixed(2) + ' KB';

                    const safeExtension = block.data.file.extension.toLowerCase().replace(/[^a-z0-9]/g, '');
                    const iconSrc = fileIcon[safeExtension] || '/images/icons/file.png';

                    // Danh sách các phần mở rộng video được hỗ trợ
                    const videoExtensions = ['mp4', 'webm', 'ogg'];

                    // Kiểm tra xem tệp có phải là video hay không
                    if (videoExtensions.includes(safeExtension)) {
                        htmlElement = `
                            <div class="attachment attachment-video">
                                <video controls class="video-player" title="${block.data.title || block.data.file.name || 'Video'}">
                                    <source src="${block.data.file.url}" type="video/${safeExtension}">
                                    Trình duyệt của bạn không hỗ trợ thẻ video.
                                </video>
                                <p class="attachment-info">
                                    <a href="${block.data.file.url}" download="${block.data.file.name || 'video.' + safeExtension}" class="attachment-link" title="Bấm để tải về">
                                        ${block.data.title || block.data.file.name || 'Video'}
                                    </a>
                                    (${block.data.file.extension.toUpperCase()}, ${sizeFormatted})
                                </p>
                            </div>
                        `;
                    } else {
                        htmlElement = `
                            <div class="attachment">
                                <a href="${block.data.file.url}" download="${block.data.file.name || 'file.' + safeExtension}" class="attachment-link" title="Bấm để tải về">
                                    <img src="${iconSrc}" alt="${block.data.file.extension} icon" class="file-icon">
                                    <strong>${block.data.title || block.data.file.name || 'Tệp'}</strong>
                                </a>
                                <p class="attachment-info">
                                    (${block.data.file.extension.toUpperCase()}, ${sizeFormatted})
                                </p>
                            </div>
                        `;
                    }
                    break;
                default:
                    htmlElement = `<p>[Không hỗ trợ hiển thị loại: ${block.type}]</p>`;
            }

            // Thêm HTML vào contentDiv
            contentDiv.innerHTML += htmlElement;
        } catch (error) {
            console.error(`Lỗi khi render block ${index} (${block.type}):`, error);
            contentDiv.innerHTML += `<p>[Lỗi render block: ${block.type}]</p>`;
        }
    });
}