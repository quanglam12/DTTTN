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
    'mp4':'../img/icons/film.png',

};

function renderContent(data) {
    const contentDiv = document.getElementById('chitietbaiviet');

    data.blocks.forEach(block => {
        let htmlElement = '';

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
                const fileSize = block.data.file.size;
                const sizeFormatted = fileSize > 1024 * 1024
                    ? (fileSize / (1024 * 1024)).toFixed(2) + ' MB'
                    : (fileSize / 1024).toFixed(2) + ' KB';

                const safeExtension = block.data.file.extension.toLowerCase().replace(/[^a-z0-9]/g, '');
                const iconSrc = fileIcon[safeExtension] || '/images/icons/file.png';

                // Tạo HTML cho file đính kèm với icon là hình ảnh
                htmlElement = `
                    <div class="attachment">
                        <a href="${block.data.file.url}" download="${block.data.file.name}" class="attachment-link" title="Bấm để tải về">
                            <img src="${iconSrc}" alt="${block.data.file.extension} icon" class="file-icon">
                            <strong>${block.data.title || block.data.file.name}</strong>
                        </a>
                        <p class="attachment-info">
                            (${block.data.file.extension.toUpperCase()}, ${sizeFormatted})
                        </p>
                    </div>
                `;
                break;
            default:
                htmlElement = `<p>[Không hỗ trợ hiển thị loại: ${block.type}]</p>`;
        }

        contentDiv.innerHTML += htmlElement;
    });
}
