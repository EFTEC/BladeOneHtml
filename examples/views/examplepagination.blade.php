<head>
    @cssbox
</head>
<body>
<h1>Testing pagination</h1>

@table( values=$products alias=$product)
    @tablehead
        @cell(text="id")
    @endtablehead
    @tablebody(id='mytable')
        @tablerows
            @cell(text=$product)
        @endtablerows
    @endtablebody
@endtable

@pagination(numpages=$totalpages current=$current  pagesize=$pagesize urlparam='_page')
</body>

