<ul class="list-group sticky-top shadow-sm" style="top: 20px; border-radius: 10px;" id="categoryList">
    <li class="list-group-item list-group-item-primary fw-bold">Menu</li>
    <!-- Danh mục sẽ được thêm động tại đây -->
</ul>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryList = document.getElementById('categoryList');
        const apiUrl = '/api/category/get_category';

        // Gọi API để lấy danh sách danh mục
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.categories.length > 0) {
                    data.categories.forEach(category => {
                        const listItem = `
                            <li class="list-group-item">
                                <a href="/index.php?page=category&id=${category.id}" class="text-decoration-none text-dark">
                                    ${category.name}
                                </a>
                            </li>
                        `;
                        categoryList.insertAdjacentHTML('beforeend', listItem);
                    });
                } else {
                    categoryList.insertAdjacentHTML('beforeend', `
                        <li class="list-group-item text-muted">Không có danh mục nào.</li>
                    `);
                }
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                categoryList.insertAdjacentHTML('beforeend', `
                    <li class="list-group-item text-danger">Không thể tải danh mục. Vui lòng thử lại sau.</li>
                `);
            });
    });
</script>
