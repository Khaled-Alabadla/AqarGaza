document.addEventListener("DOMContentLoaded", () => {
  const paginationDotsContainer = document.querySelector(".pagination");
  const blogPostsGrid = document.querySelector(".posts-listing-grid");
  const allBlogPosts = Array.from(
    document.querySelectorAll(".posts-listing-grid .post-card")
  );

  const postsPerPage = 4;
  let currentPage = 1;

  function renderBlogPosts(page) {
    blogPostsGrid.innerHTML = "";
    const start = (page - 1) * postsPerPage;
    const end = start + postsPerPage;
    const postsToRender = allBlogPosts.slice(start, end);

    postsToRender.forEach((post) => {
      blogPostsGrid.appendChild(post.cloneNode(true));
    });
  }

  function setupPagination() {
    const totalPages = Math.ceil(allBlogPosts.length / postsPerPage);
    paginationDotsContainer.innerHTML = "";

    if (totalPages <= 1) {
      paginationDotsContainer.style.display = "none";
      return;
    } else {
      paginationDotsContainer.style.display = "flex";
    }

    for (let i = 1; i <= totalPages; i++) {
      const dot = document.createElement("span");
      dot.classList.add("pagination-dot");
      if (i === currentPage) {
        dot.classList.add("active");
      }
      dot.dataset.page = i;
      dot.addEventListener("click", (event) => {
        currentPage = parseInt(event.target.dataset.page);
        renderBlogPosts(currentPage);
        updatePaginationDots();
      });
      paginationDotsContainer.appendChild(dot);
    }
  }

  function updatePaginationDots() {
    document.querySelectorAll(".pagination-dot").forEach((dot) => {
      dot.classList.remove("active");
      if (parseInt(dot.dataset.page) === currentPage) {
        dot.classList.add("active");
      }
    });
  }

  renderBlogPosts(currentPage);
  setupPagination();
});
