# multiple-options-search

- This code implements multiple options search in Laravel Query Builder.
- Based on available paramters the query is changed accordingly.
- This also implements eager loading to reduce the number of query executions to get the required data.
- Initially this code runs on an average 80+ queries but after the implementation of eager loading and dynamic query it has been reduced to 5 average queries.
