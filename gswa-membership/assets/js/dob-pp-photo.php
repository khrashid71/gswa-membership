// Listen for photo file selection changes
<script>
document.getElementById('photo').addEventListener('change', function() {
    const file = this.files[0]; // Gets the selected file
    const errorDiv = document.getElementById('photo-error');
    
    if (file) {
        // Define allowed image formats
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        // Max allowed size: 2 Megabytes (2 * 1024 * 1024 bytes)
        const maxSize = 2 * 1024 * 1024; 
        
        // 1. Check File Format
        if (!allowedTypes.includes(file.type)) {
            errorDiv.textContent = "Invalid format! Only JPG, PNG, and WebP are allowed. / অগ্রহণযোগ্য ফরম্যাট! শুধুমাত্র JPG, PNG এবং WebP আপলোড করা যাবে।";
            errorDiv.style.display = "block";
            this.value = ""; // Clears the file input
            return; // Stops further execution
        }
        
        // 2. Check File Size
        if (file.size > maxSize) {
            errorDiv.textContent = "File is too large! Maximum size allowed is 2MB. / ফাইলটি অনেক বড়! সর্বোচ্চ সাইজ ২ মেগাবাইট।";
            errorDiv.style.display = "block";
            this.value = ""; // Clears the file input
        } else {
            errorDiv.style.display = "none"; // Hides error if file passes all checks
        }
    }
});
</script>
