<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>歷屆作品瀏覽</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007BFF;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
        }
        .navbar a {
            color: white;
            text-decoration: none;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .project-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .project-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .project-item h3 {
            margin: 0 0 10px;
            color: #007BFF;
        }
        .project-item img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .project-item a {
            color: #007BFF;
            text-decoration: none;
            margin-right: 10px;
        }
        .filter-section {
            margin-bottom: 20px;
            text-align: center;
        }
        .filter-section input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>高雄大學學生創意競賽</div>
        <div>
            <a href="console.php">返回</a>
        </div>
    </div>
    
    <div class="container">
        <h1>歷屆學生作品展示</h1>
        
        <div class="filter-section">
            <input type="text" id="searchInput" placeholder="搜尋作品名稱" onkeyup="filterProjects()">
        </div>

        <div id="projectList" class="project-list">
            <!-- 作品將在這裡動態生成 -->
        </div>
    </div>

    <script>
        function renderAllProjects() {
            const projects = JSON.parse(localStorage.getItem('projects')) || [];
            const projectList = document.getElementById('projectList');
            projectList.innerHTML = '';

            if (projects.length === 0) {
                projectList.innerHTML = '<p style="text-align:center;">目前尚無作品</p>';
                return;
            }

            projects.forEach(project => {
                const projectDiv = document.createElement('div');
                projectDiv.className = 'project-item';
                projectDiv.innerHTML = `
                    <h3>${project.title}</h3>
                    <p>${project.description}</p>
                    ${project.youtubeLink ? `<p><a href="${project.youtubeLink}" target="_blank">成果展示</a></p>` : ''}
                    ${project.githubLink ? `<p><a href="${project.githubLink}" target="_blank">GitHub</a></p>` : ''}
                    ${project.imageUrl ? `<img src="${project.imageUrl}" alt="作品圖片">` : ''}
                    ${project.pdfUrl ? `<p><a href="${project.pdfUrl}" target="_blank">PDF 文件</a></p>` : ''}
                `;
                projectList.appendChild(projectDiv);
            });
        }

        function filterProjects() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const projects = document.getElementsByClassName('project-item');
            
            Array.from(projects).forEach(project => {
                const title = project.querySelector('h3').textContent.toLowerCase();
                project.style.display = title.includes(searchInput) ? 'block' : 'none';
            });
        }

        // 頁面載入時渲染所有作品
        window.onload = renderAllProjects;
    </script>
</body>
</html>