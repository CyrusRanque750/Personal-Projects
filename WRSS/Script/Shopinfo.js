document.addEventListener('DOMContentLoaded', () => {
    const handleFileChange = (inputId, imgId) => {
        document.getElementById(inputId).onchange = function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => document.getElementById(imgId).src = e.target.result;
                reader.readAsDataURL(file);
            }
        };
    };

    handleFileChange('input-profile', 'profilePic');
    handleFileChange('input-permit', 'permit');
});
