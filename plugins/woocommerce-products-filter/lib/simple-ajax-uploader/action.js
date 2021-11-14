function woof_init_ext_uploader(abspath, location, url) {
//https://github.com/LPology/Simple-Ajax-Uploader
    var btn = document.getElementById('upload-btn'),
            wrap = document.getElementById('pic-progress-wrap'),
            picBox = document.getElementById('picbox'),
            errBox = document.getElementById('errormsg');

    var uploader = new ss.SimpleUpload({
        customHeaders: {
            'action': 'woof_upload_ext',
            'abspath': abspath,
            'location': location
        },
        button: btn,
        url: url,
        //sessionProgressUrl: '/code/ajaxuploader/sessionProgress.php',
        name: 'zipfile',
        multiple: true,
        multipart: false,
        maxUploads: 10,
        maxSize: 20000,
        queue: false,
        allowedExtensions: ['zip'],
        accept: 'application/zip',
        debug: false,
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onSubmit: function (filename, ext) {
            woof_show_info_popup('Extension file uploading ...');
            var prog = document.createElement('div'),
                    outer = document.createElement('div'),
                    bar = document.createElement('div'),
                    size = document.createElement('div'),
                    self = this;

            prog.className = 'prog';
            size.className = 'size';
            outer.className = 'progress progress-striped';
            bar.className = 'progress-bar progress-bar-success';

            outer.appendChild(bar);
            prog.appendChild(size);
            prog.appendChild(outer);
            wrap.appendChild(prog); // 'wrap' is an element on the page

            self.setProgressBar(bar);
            self.setProgressContainer(prog);
            self.setFileSizeBox(size);

            errBox.innerHTML = '';
            btn.value = 'Choose another zip file';
        },
        onSizeError: function () {
            errBox.innerHTML = 'Files may not exceed 20M.';
        },
        onExtError: function () {
            errBox.innerHTML = 'Invalid file type. Please select ZIP only.';
        },
        onComplete: function (file, response, btn) {
            woof_show_info_popup('Extension is uploaded!');
            if (typeof response.ext_info.title != 'undefined') {
                var tpl = jQuery('#woof_ext_tpl').html();
                tpl = tpl.replace(/__NAME__/g, "woof_settings[activated_extensions][]");
                tpl = tpl.replace(/__IDX__/g, response.ext_info.idx);
                tpl = tpl.replace(/__TITLE__/g, response.ext_info.title);
                tpl = tpl.replace(/__VERSION__/g, response.ext_info.version);
                tpl = tpl.replace(/__DESCRIPTION__/g, response.ext_info.description);
                jQuery('ul.woof_custom_extensions').append(tpl);
            } else {
                alert('It has been uploaded without the *.dat file inside!');
            }
            woof_hide_info_popup();
        }
    });
}

