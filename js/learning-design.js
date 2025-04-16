    // JavaScript to force light mode
    document.addEventListener("DOMContentLoaded", () => {
        document.documentElement.setAttribute("data-theme", "light");
    });

    var swiper = new Swiper(".mySwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true
        },
        pagination: {
            el: ".swiper-pagination"
        }
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(
            function () {
                alert("Copied to clipboard: " + text);
            },
            function (err) {
                alert("Failed to copy: ", err);
            }
        );
    }

    document.addEventListener("DOMContentLoaded", function () {
        var chartDom = document.getElementById("sections-learning-experiences");
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            series: {
                type: "sankey",
                layout: "none",
                emphasis: {
                    focus: "adjacency"
                },
                label: {
                    show: true,
                    formatter: function (params) {
                        return "{icon|} " + params.name;
                    },
                    rich: {
                        icon: {
                            height: 20,
                            align: "center",
                            backgroundColor: {
                                image: "https://placehold.co/600x600/EEE/31343C"
                            }
                        }
                    }
                },
                nodeWidth: 20, // Adjust node width
                nodeGap: 10, // Adjust gap between nodes
                lineStyle: {
                    normal: {
                        color: "source",
                        curveness: 0.5, // Adjust line curveness
                        opacity: 0.6,
                        width: 3 // Adjust line width
                    }
                },
                draggable: false, // Add this line to disable dragging
                data: [
                    { name: "LE1" },
                    { name: "LE2" },
                    { name: "LE3" },
                    { name: "LT1" },
                    { name: "LT2" },
                    { name: "LT3" },
                    { name: "LT4" }
                ],
                links: [
                    { source: "LE1", target: "LT1", value: 1 },
                    { source: "LE1", target: "LT2", value: 1 },
                    { source: "LE2", target: "LT2", value: 1 },
                    { source: "LE3", target: "LT3", value: 1 },
                    { source: "LE3", target: "LT1", value: 1 },
                    { source: "LE3", target: "LT4", value: 1 }
                ]
            }
        };

        option && myChart.setOption(option);

        // Event listener for clicks on the chart
        myChart.on("click", function (params) {
            if (params.componentType === "series") {
                document.getElementById(
                    "modalContent"
                ).textContent = `You clicked on ${params.name}`;
                openModal();
            }
        });

        // Functions to open and close the modal
        function openModal() {
            document.getElementById("infoModal").classList.add("is-active");
        }

        function closeModal() {
            document.getElementById("infoModal").classList.remove("is-active");
        }

        // Event listener to close the modal
        document
            .querySelectorAll(".modal-close, .modal-background")
            .forEach((element) => {
                element.addEventListener("click", closeModal);
            });

        // Close the modal when the escape key is pressed
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    });

