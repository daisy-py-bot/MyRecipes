    // // Example number of recipes per month (12 months)
    // const recipeData = [120, 30, 67, 120, 120, 67, 80, 50, 78, 34, 50, 1]; // Updated to match HTML structure

    // // Calculate the maximum value to scale the bars
    // const maxValue = Math.max(...recipeData);

    // // Select all bars
    // const bars = document.querySelectorAll('.bar');

    // bars.forEach((bar, index) => {
    //     // Scale the bar height based on the maximum value
    //     const height = (recipeData[index] / maxValue) * 100; // Normalize to percentage
    //     bar.style.height = `${height}%`; // Dynamically set the height
    //     bar.setAttribute('data-value', `${recipeData[index]} recipes`); // Update the data attribute to match the data
    // });