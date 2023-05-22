<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Drag and Drop</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <style>
        .dragging {
            opacity: 0.5;
        }

        .drag {
            background: #fff;
            padding: 20px;
            margin-bottom: 10px;
        }

        .drop {
            background: #fff;
            padding: 20px;
            margin-bottom: 10px;
            min-height: 100px;
        }

        .drag {
            transition: transform 0.2s;
            cursor: move;
        }

        .drop {
            transition: background-color 0.2s;
        }

        .dragging {
            transform: scale(1.2);
        }

        .drop.dragging-over {
            background-color: #efefef;
        }

        .dragging {
            opacity: 0.5;
        }

        .card-body {
            display: flex;
        }

        .col-9 {
            margin-left: auto;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Drag and Drop</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header">
                                <h3>Drag</h3>
                            </div>
                            <!-- call appi and render all responses as drags -->
                            <div class="card-body">
                                <div class="drag-container">
                                    <?php
                                        $url = "http://localhost/examenproject/backend/?item=all";
                                        $response = file_get_contents($url);
                                        $result = json_decode($response);
                                        foreach ($result as $key => $value) {
                                            foreach ($value as $key => $value) {
                                                echo "<div class='drag' draggable='true'>" . $value . "</div>";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9" style="float: right;">
                        <div class="card">
                            <div class="card-header">
                                <h3>Drop</h3>
                            </div>
                            <div class="card-body">
                                <div class="drop">
                                    <p>Drop here</p>
                                </div>
                                <div class="drop">
                                    <p>Drop here</p>
                                </div>
                                <div class="drop">
                                    <p>Drop here</p>
                                </div>
                                <div class="drop">
                                    <p>Drop here</p>
                                </div>
                                <div class="drop">
                                    <p>Drop here</p>
                                </div>
                                <div class="drop">
                                    <p>Drop here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p>Drag and Drop</p>
            </div>
        </div>
    </div>
    <script>
        const draggables = document.querySelectorAll('.drag');
        const containers = document.querySelectorAll('.drop');

        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', () => {
                draggable.classList.add('dragging');
            });

            draggable.addEventListener('dragend', () => {
                draggable.classList.remove('dragging');
            });
        });

        containers.forEach(container => {
            container.addEventListener('dragover', e => {
                e.preventDefault();
                if (!container.classList.contains('drag')) {
                    const afterElement = getDragAfterElement(container, e.clientY);
                    const draggable = document.querySelector('.dragging');
                    if (afterElement == null) {
                        container.appendChild(draggable);
                    } else {
                        container.insertBefore(draggable, afterElement);
                    }
                }
            });
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.drag:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return {
                        offset: offset,
                        element: child
                    };
                } else {
                    return closest;
                }
            }, {
                offset: Number.NEGATIVE_INFINITY
            }).element;
        }

    </script>
</body>

</html>
