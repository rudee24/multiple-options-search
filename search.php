<?php
public function index(Request $request)
    {
        $results = array();
        $a = 0;
        $subcategories = $request->subcategory;
        $categories = $request->categories;
        $results = Event::with('subcategories', 'categories', 'venue', 'featuredImage')
            ->where(function ($q) use ($request, $subcategories) {
                $k = 0;
                if ($request->keywords != '') {
                    $k = 1;
                }
                foreach ($request->all() as $key => $value) {
                    if ($key == 'state') {
                        if (!empty($request->state)) {
                            $q->whereIn('state', $request->state);
                        }
                    } elseif ($key == 'country') {
                        if (!empty($request->country)) {
                            $q->whereIn('country', $request->country);
                        }
                    } elseif ($key == 'region') {
                        if (!empty($request->region)) {
                            $q->whereIn('region', $request->region);
                        }
                    } elseif ($key == 'keywords') {
                        if ($value != '') {
                            $q->where('title', 'LIKE', '%' . $request->keywords . '%');
                        }
                    } elseif ($key == 'start_date') {
                        if ($value != '') {
                            // $q->where(function ($query) {
                            $q->whereDate('end_date', '>=', date_format(date_create($request->start_date), "Y-m-d"))
                                ->WhereDate('start_date', '<=', date_format(date_create($request->start_date), "Y-m-d"));
                            // });
                        } else {
                            if ($k != 1) {
                                $q->whereDate('start_date', '>=', date_format(date_create(now()), "Y-m-d"));
                            }
                        }
                    }
                }
                $q->where('hidden', '!=', '1');
            })
            ->when($subcategories, function ($query) use ($subcategories) {
                $query->whereHas('subcategories', function ($q) use ($subcategories) {
                    return $q->whereIn('subcategories.id', $subcategories);
                });
            })
            ->when($categories, function ($query) use ($categories) {
                $query->whereHas('categories', function ($q) use ($categories) {
                    return $q->whereIn('categories.id', $categories);
                });
            })
            ->orWhere(function ($q) use ($request, $subcategories) {
                foreach ($request->all() as $key => $value) {
                    if ($key == 'subcategory') {
                        //print_r ($subcategories);
                        // if(!empty($request->subcategory)){  
                        //     $q->whereHas('subcategories',function($query) use ($subcategories){
                        //         return $query->whereIn('subcategories.id',$subcategories);
                        //     });
                        // }
                    } elseif ($key == 'state') {
                        if (!empty($request->state)) {
                            $q->whereIn('state', $request->state);
                        }
                    } elseif ($key == 'country') {
                        if (!empty($request->country)) {
                            $q->whereIn('country', $request->country);
                        }
                    } elseif ($key == 'region') {
                        if (!empty($request->region)) {
                            $q->whereIn('region', $request->region);
                        }
                    } elseif ($key == 'start_date') {
                        $q->WhereDate('start_date', '>=', date_format(date_create($request->start_date), "Y-m-d"));
                    } elseif ($key == 'keywords') {
                        if ($value != '') {
                            $q->where('title', 'LIKE', '%' . $request->keywords . '%');
                        }
                    }
                }
                $q->where('hidden', '!=', '1');
            })
            ->when($subcategories, function ($query) use ($subcategories) {
                $query->whereHas('subcategories', function ($q) use ($subcategories) {
                    return $q->whereIn('subcategories.id', $subcategories);
                });
            })
            ->when($categories, function ($query) use ($categories) {
                $query->whereHas('categories', function ($q) use ($categories) {
                    return $q->whereIn('categories.id', $categories);
                });
            })
            ->orderBy('start_date')->paginate(19)->onEachSide(2);
        return view('search', compact('results'));
    }