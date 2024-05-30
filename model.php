<?php
#[Attribute]
class Table
{
    public function __construct(public string $name)
    {
    }
}

#[Attribute]
class Column
{
    public function __construct(public string $name, public string $type, public bool $primary = false, public bool $autoIncrement = false, public bool $nullable = true)
    {
    }
}
#[Table("products")]
class Product
{
    #[Column("id", "INT", primary: true, autoIncrement: true)]
    public int $id = 0;

    #[Column("name", "VARCHAR(255)")]
    public string $name;

    #[Column("price", "DECIMAL(10,2)")]
    public float $price;

    #[Column("description", "TEXT", nullable: true)]
    public ?string $description;

    #[Column("create_date", "DATETIME", nullable: false)]
    public DateTime $create_date;

    #[Column("quantity", "INT")]
    public int $quantity;

    public function __construct($name, $description, $price, $quantity)
    {
        $this->name = $name;
        $this->create_date = new DateTime();
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
