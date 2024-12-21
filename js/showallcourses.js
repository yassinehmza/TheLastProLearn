fetch('../backend/getallcourses.php')
  .then(response => response.json())
  .then(courses => {
    console.log(courses);  // Log the response to check if it’s correct
    const coursesContainer = document.getElementById('courses');

    if (courses.length === 0) {
      coursesContainer.innerHTML += '<p>No courses available at the moment.</p>';
      return;
    }

    courses.forEach(course => {
      const courseItem = document.createElement('div');
      courseItem.classList.add('course-item');
      courseItem.innerHTML = `
        <img src="${course.image || 'https://via.placeholder.com/350x200'}" alt="${course.title}">
        <h3>${course.title}</h3>
        <p>${course.description}</p>
        <a href="#" class="get-started" data-course-id="${course.id}">Enroll Now</a>
      `;
      coursesContainer.appendChild(courseItem);
    });
  })
  .catch(error => {
    console.error('Error fetching courses:', error);
  });
