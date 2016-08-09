# Tree - Nestedset behavior extension for Doctrine 2

# tree - Doctrine无限分类功能扩展

**Tree** nested behavior will implement the standard Nested-Set behavior
on your Entity. Tree supports different strategies. Currently it supports
**nested-set**, **closure-table** and **materialized-path**. Also this behavior can be nested
with other extensions to translate or generated slugs of your tree nodes.

Features:

- Materialized Path strategy for ORM and ODM (MongoDB)
- Closure tree strategy, may be faster in some cases where ordering does not matter
- Support for multiple roots in nested-set
- No need for other managers, implementation is through event listener
- Synchronization of left, right values is automatic
- Can support concurrent flush with many objects being persisted and updated
- Can be nested with other extensions
- Annotation, Yaml and Xml mapping support for extensions

Thanks for contributions to:

- **[comfortablynumb](http://github.com/comfortablynumb) Gustavo Falco** for Closure and Materialized Path strategy
- **[everzet](http://github.com/everzet) Kudryashov Konstantin** for TreeLevel implementation
- **[stof](http://github.com/stof) Christophe Coevoet** for getTreeLeafs function

**树状**嵌套功能将在你的实体实现基本的无限分类功能。Tree支持不同的策略。当前它支持**无限分类**，**闭包表格**和**具体化路径**。这个功能也可以嵌套在其他扩展来为你的树节点进行翻译或生成slug。


特性:

- 具体化路径策略可用在ORM和ODM(MongoDB)
- 闭包树策略，在一些排序无关紧要的场景会更快。
- 在无限分类里支持多根。
- 不需要其他管理器，全部通过事件监听器实现。
- 自动同步左，右的值。
- 支持同时多对象持久化和更新。
- 可以嵌套其他扩展
- 扩展的Annotation, Yaml and Xml 映射支持

感谢以下诸位的贡献:

- **[comfortablynumb](http://github.com/comfortablynumb) Gustavo Falco** ，实现了闭包和具体化路径策略
- **[everzet](http://github.com/everzet) Kudryashov Konstantin** ，实现了TreeLevel
- **[stof](http://github.com/stof) Christophe Coevoet** 实现了getTreeLeafs函数

**略过以下更新日志的翻译**

Update **2015-12-23**

- Added Tree repository traits for easier extending tree functionalities in your repositories. [Usage example here](#tree-repositories)

Update **2012-06-28**

- Added "buildTree" functionality support for Closure and Materialized Path strategies

Update **2012-02-23**

- Added a new strategy to support the "Materialized Path" tree model. It works with ODM (MongoDB) and ORM.

Update **2011-05-07**

- Tree is now able to act as **closure** tree, this strategy was refactored
  and now fully functional. It is much faster for file-folder trees for instance
  where you do not care about tree ordering.

Update **2011-04-11**

- Made in memory node synchronization, this change does not require clearing the cached nodes after any updates
  to nodes, except **recover, verify and removeFromTree** operations.

Update **2011-02-08**

- Refactored to support multiple roots
- Changed the repository name, relevant to strategy used
- New [annotations](#annotations) were added


Update **2011-02-02**

- Refactored the Tree to the ability on supporting different tree models
- Changed the repository location in order to support future updates

**Note:**

- After using a NestedTreeRepository functions: **verify, recover, removeFromTree** it is recommended to clear the EntityManager cache
  because nodes may have changed values in database but not in memory. Flushing dirty nodes can lead to unexpected behaviour.
- Closure tree implementation is experimental and not fully functional, so far not documented either
- Public [Tree repository](http://github.com/l3pp4rd/DoctrineExtensions "Tree extension on Github") is available on github
- Last update date: **2012-02-23**



**注意:**

- 在使用NestedTreeRepository函数：**verify**, **recover**, **removeFromTree**之后，建议清除EntityManager缓存。  
- 因为节点的值可能已经在数据库里修改了而没在内存里变更。这样持久化脏节点可能会引起不可预知的行为。
- 闭包树的实现还是实验性的，不完全的功能，因此还没文档。
- 公共的[Tree repository](http://github.com/l3pp4rd/DoctrineExtensions "Tree extension on Github")在github可得。
- 最后更新日期：**2012-02-23**



**Portability:**

- **Tree** is now available as [Bundle](http://github.com/stof/StofDoctrineExtensionsBundle)
  ported to **Symfony2** by **Christophe Coevoet**, together with all other extensions

This article will cover the basic installation and functionality of **Tree** behavior

Content:

- [Including](#including-extension) the extension
- Tree [annotations](#annotations)
- Entity [example](#entity-mapping)
- [Yaml](#yaml-mapping) mapping example
- [Xml](#xml-mapping) mapping example
- Basic usage [examples](#basic-examples)
- Build [html tree](#html-tree)
- Advanced usage [examples](#advanced-examples)
- [Materialized Path](#materialized-path)
- [Closure Table](#closure-table)
- [Repository methods (all strategies)](#repository-methods)

<a name="including-extension"></a>

**可移植性:**

- **Tree** 可以作为一个 [Bundle ](http://github.com/stof/StofDoctrineExtensionsBundle) 获得
  到**Symfony2**的接口由**Christophe Coevoet**创建, 与其他所有扩展一起。

本章将涵盖基础安装和**Tree**的功能函数

内容：

- [Including](#including-extension) 扩展
- Tree [annotations](#annotations)
- 实体 [example](#entity-mapping)
- [Yaml](#yaml-mapping) 映射例子
- [Xml](#xml-mapping) 映射例子
- 基本使用 [examples](#basic-examples)
- 创建 [html tree](#html-tree)
- 高级用法[例子](#advanced-examples)
- [具体化路径](#materialized-path)
- [闭包表](#closure-table)
- [Repository方法](all strategies)



## Setup and autoloading

Read the [documentation](http://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/annotations.md#em-setup)
or check the [example code](http://github.com/l3pp4rd/DoctrineExtensions/tree/master/example)
on how to setup and use the extensions in the most optimized way.

<a name="entity-mapping"></a>

## Tree Entity example:

**Note:** Node interface is not necessary, except in cases where
you need to identify and entity as being a Tree Node. The metadata is loaded only once when the
cache is activated

## 安装和自动加载

阅读[文档](http://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/annotations.md#em-setup)

或查看[示例代码](http://github.com/l3pp4rd/DoctrineExtensions/tree/master/example)

看看怎样以最优方式安装和使用扩展。

## Tree 实体示例:

**注意：**除非在你需要识别以及实体作为一个数节点的情况下，否则Node interface不是必须的。metadata在cache开启时只加载一次。

``` php
<?php
namespace Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categories")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
```

<a name="annotations"></a>

### Tree annotations:

- **@Gedmo\Mapping\Annotation\Tree(type="strategy")** this **class annotation** sets the tree strategy by using the **type** parameter.
  Currently **nested**, **closure** or **materializedPath** strategies are supported. An additional "activateLocking" parameter
  is available if you use the "Materialized Path" strategy with MongoDB. It's used to activate the locking mechanism (more on that
  in the corresponding section).
- **@Gedmo\Mapping\Annotation\TreeLeft** field is used to store the tree **left** value
- **@Gedmo\Mapping\Annotation\TreeRight** field is used to store the tree **right** value
- **@Gedmo\Mapping\Annotation\TreeParent** will identify the column as the relation to **parent node**
- **@Gedmo\Mapping\Annotation\TreeLevel** field is used to store the tree **level**
- **@Gedmo\Mapping\Annotation\TreeRoot** field is used to store the tree **root** id value or identify the column as the relation to **root node**
- **@Gedmo\Mapping\Annotation\TreePath** (Materialized Path only) field is used to store the **path**. It has an
  optional parameter "separator" to define the separator used in the path.
- **@Gedmo\Mapping\Annotation\TreePathSource** (Materialized Path only) field is used as the source to
   construct the "path"
- **@Gedmo\Mapping\Annotation\TreeLockTime** (Materialized Path - ODM MongoDB only) field is used if you need to
  use the locking mechanism with MongoDB. It persists the lock time if a root node is locked (more on that in the corresponding
  section).

### Tree annotations:

- **@Gedmo\Mapping\Annotation\Tree(type="strategy")** 这个 **类 annotation** 通过使用**type**参数设定了树策略。
  当前支持 **nested**, **closure** 或 **materializedPath** 策略。如果你在MongoDB下使用“具体化路径”策略还有一个额外的"activateLocking"参数。它用来激活锁定机制（详述见对应部分）。
- **@Gedmo\Mapping\Annotation\TreeLeft** 字段用来存储**left**值。
- **@Gedmo\Mapping\Annotation\TreeRight** 字段用来存储**right**值。
- **@Gedmo\Mapping\Annotation\TreeParent** 将用来识别相关的**父节点**。
- **@Gedmo\Mapping\Annotation\TreeLevel** 字段用来存储树**level**。
- **@Gedmo\Mapping\Annotation\TreeRoot** 字段用来存储树 **root** id 值或相关root节点的识别列号
- **@Gedmo\Mapping\Annotation\TreePath** (仅对具体化路径) 字段用来存储**path**。有一个选项参数"separator"用来定义路径里的分隔符。
- **@Gedmo\Mapping\Annotation\TreePathSource** (仅对具体化路径) 字段用来作为构成"path"的源。
- **@Gedmo\Mapping\Annotation\TreeLockTime** (只针对MongoDB ODM的具体化路径 ) 如果你需要在MongoDB使用锁定机制，使用此字段。如果根节点被锁定它将保存锁定时间 (详述见相关部分).



<a name="yaml-mapping"></a>

## Yaml mapping example

Yaml mapped Category: **/mapping/yaml/Entity.Category.dcm.yml**



# Yaml 映射示例

Yaml映射的分类：**/mapping/yaml/Entity.Category.dcm.yml**

```
---
Entity\Category:
  type: entity
  repositoryClass: Gedmo\Tree\Entity\Repository\NestedTreeRepository
  table: categories
  gedmo:
    tree:
      type: nested
  id:
    id:
      type: integer
      generator:
        strategy: AUTO
  fields:
    title:
      type: string
      length: 64
    lft:
      type: integer
      gedmo:
        - treeLeft
    rgt:
      type: integer
      gedmo:
        - treeRight
    lvl:
      type: integer
      gedmo:
        - treeLevel
  manyToOne:
    root:
      targetEntity: Entity\Category
      joinColumn:
        referencedColumnName: id
        onDelete: CASCADE
      gedmo:
        - treeRoot
    parent:
      targetEntity: Entity\Category
      inversedBy: children
      joinColumn:
        referencedColumnName: id
        onDelete: CASCADE
      gedmo:
        - treeParent
  oneToMany:
    children:
      targetEntity: Entity\Category
      mappedBy: parent
      orderBy:
        lft: ASC
```

<a name="xml-mapping"></a>

## Xml mapping example



## Xml 映射示例



``` xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:gedmo="http://Atlantic18.github.io/DoctrineExtensions/schemas/orm/doctrine-extensions-3.0.xsd">

    <entity name="Mapping\Fixture\Xml\NestedTree" table="nested_trees" repository-class="Gedmo\Tree\Entity\Repository\NestedTreeRepository">

        <indexes>
            <index name="name_idx" columns="name"/>
        </indexes>

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>

        <field name="name" type="string" length="128"/>
        <field name="left" column="lft" type="integer">
            <gedmo:tree-left/>
        </field>
        <field name="right" column="rgt" type="integer">
            <gedmo:tree-right/>
        </field>
        <field name="level" column="lvl" type="integer">
            <gedmo:tree-level/>
        </field>

        <many-to-one field="root" target-entity="NestedTree">
            <join-column name="tree_root" referenced-column-name="id" on-delete="CASCADE"/>
            <gedmo:tree-root/>
        </many-to-one>

        <many-to-one field="parent" target-entity="NestedTree" inversed-by="children">
            <join-column name="parent_id" referenced-column-name="id" on-delete="CASCADE"/>
            <gedmo:tree-parent/>
        </many-to-one>

        <one-to-many field="children" target-entity="NestedTree" mapped-by="parent">
            <order-by>
                <order-by-field name="left" direction="ASC" />
            </order-by>
        </one-to-many>

        <gedmo:tree type="nested"/>

    </entity>

</doctrine-mapping>
```

<a name="basic-examples"></a>

## Basic usage examples:

### To save some **Categories** and generate tree:



## 基本用法示例：

### 要保存一些种类并生成树：

``` php
<?php
$food = new Category();
$food->setTitle('Food');

$fruits = new Category();
$fruits->setTitle('Fruits');
$fruits->setParent($food);

$vegetables = new Category();
$vegetables->setTitle('Vegetables');
$vegetables->setParent($food);

$carrots = new Category();
$carrots->setTitle('Carrots');
$carrots->setParent($vegetables);

$this->em->persist($food);
$this->em->persist($fruits);
$this->em->persist($vegetables);
$this->em->persist($carrots);
$this->em->flush();
```

The result after flush will generate the food tree:

在持久化后的结果会生成food树：

```
/food (1-8)
    /fruits (2-3)
    /vegetables (4-7)
        /carrots (5-6)
```

## Tree Repositories
To add tree functionalities and methods to your repository you can use traits `NestedTreeRepository`, `MaterializedPathRepository` or `ClosureTreeRepository` like below:

## Tree仓库

要添加树功能和方法到你的仓库，你可以使用 **trait** `NestedTreeRepository`, `MaterializedPathRepository`或 `ClosureTreeRepository`，如下：

```php
namespace YourNamespace\Repository;

use Gedmo\Tree\Traits\Repository\ORM\NestedTreeRepositoryTrait;

class CategoryRepository extends EntityRepository
{
    use NestedTreeRepositoryTrait; // 或 MaterializedPathRepositoryTrait 或 ClosureTreeRepositoryTrait.

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->initializeTreeRepository($em, $class);
    }
}
```
```php
namespace YourNamespace\Repository;

/**
 * @Gedmo\Tree(type="nested")
 * @Entity(repositoryClass="YourNamespace\Repository\CategoryRepository")
 */
class Category
{
    //...
}
```

### Using functions

### 使用函数：

``` php
<?php
$repo = $em->getRepository('Entity\Category');

$food = $repo->findOneByTitle('Food');
echo $repo->childCount($food);
// prints: 3
echo $repo->childCount($food, true/*direct*/);
// prints: 2
$children = $repo->children($food);
// $children contains:
// 3 nodes
$children = $repo->children($food, false, 'title');
// will sort the children by title
$carrots = $repo->findOneByTitle('Carrots');
$path = $repo->getPath($carrots);
/* $path contains:
   0 => Food
   1 => Vegetables
   2 => Carrots
*/

// verification and recovery of tree
// 证实和恢复树
$repo->verify();
// can return TRUE if tree is valid, or array of errors found on tree
// 如果树是you有效的会返回TRUE，无效则会返回在树里找到的错误数组
$repo->recover();
$em->flush(); // important: flush recovered nodes // 重要：flush会恢复节点
// if tree has errors it will try to fix all tree nodes
// 如果树有错误，它将尝试修复全部树节点

// UNSAFE: be sure to backup before running this method when necessary, if you can use $em->remove($node);
// which would cascade to children
// single node removal
// 不安全：如果你可以使用$em->remove($node)，当必须执行这个方法前请确保做了备份。
// 单一节点删除，其将关联影响其子孙节点 

$vegies = $repo->findOneByTitle('Vegetables');
$repo->removeFromTree($vegies);
$em->clear(); // clear cached nodes // 清除缓存节点
// it will remove this node from tree and reparent all children
// 将删除这个节点，并对全部子节点重定义父节点

// reordering the tree
// 重新排序树
$food = $repo->findOneByTitle('Food');
$repo->reorder($food, 'title');
// it will reorder all "Food" tree node left-right values by the title
// 将根据title重新排序所有"Food"树节点的left-right值
```

### Inserting node in different positions

### 在不同的位置插入节点

``` php
<?php
$food = new Category();
$food->setTitle('Food');

$fruits = new Category();
$fruits->setTitle('Fruits');

$vegetables = new Category();
$vegetables->setTitle('Vegetables');

$carrots = new Category();
$carrots->setTitle('Carrots');

$treeRepository
    ->persistAsFirstChild($food)
    ->persistAsFirstChildOf($fruits, $food)
    ->persistAsLastChildOf($vegetables, $food)
    ->persistAsNextSiblingOf($carrots, $fruits);

$em->flush();
```

For more details you can check the **NestedTreeRepository**

Moving up and down the nodes in same level:

Tree example:

详述见**NetstedTreeRepository**

在同一等级升降节点：

树示例：

```
/Food
    /Vegetables
        /Onions
        /Carrots
        /Cabbages
        /Potatoes
    /Fruits
```

Now move **carrots** up by one position

现在提升**caroots**一个位置

``` php
<?php
$repo = $em->getRepository('Entity\Category');
$carrots = $repo->findOneByTitle('Carrots');
// move it up by one position
// 向上移动一个位置
$repo->moveUp($carrots, 1);
```

Tree after moving the Carrots up:

提升Carrots之后的树：

```
/Food
    /Vegetables
        /Carrots <- 提升上来了
        /Onions
        /Cabbages
        /Potatoes
    /Fruits
```

Moving **carrots** down to the last position

降低**carrots**到最后的位置

``` php
<?php
$repo = $em->getRepository('Entity\Category');
$carrots = $repo->findOneByTitle('Carrots');
// move it down to the end
// 将它向下移动到结尾
$repo->moveDown($carrots, true);
```

Tree after moving the Carrots down as last child:

将Carrots降到最后的子节点之后的树：

```
/Food
    /Vegetables
        /Onions
        /Cabbages
        /Potatoes
        /Carrots <- 降到结尾
    /Fruits
```

**Note:** the tree repository functions **verify, recover, removeFromTree**
will require you to clear the cache of the Entity Manager because left-right values will differ.
So after that use **$em->clear();** if you will continue using the nodes after these operations.

### Extend abstract repositores
If you do not want to use traits and need a simple tree repository you can extend like below:

**注意：** 树仓库函数**veriry**, **recover**, **removeFromeTree**将需要清除Entity Manager的缓存，因为left-right值将不同。如果你在这些操作后想继续使用这个节点，就在之后用**$em->clear();** 

### 扩展抽象仓库

如果你不想使用traits，并需要一个简单树仓库，你可以像下边这样扩展：

``` php
<?php
namespace YourNamespace\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    // your code here
}

// and then on your entity link to this repository

/**
 * @Gedmo\Tree(type="nested")
 * @Entity(repositoryClass="YourNamespace\Repository\CategoryRepository")
 */
class Category
{
    //...
}
```

<a name="html-tree"></a>

## Create html tree:

### Retrieving the whole tree as an array

If you would like to load the whole tree as a node array hierarchy use:



## 创建html树：

### 作为一个数组获取整体树

如果你想要作为一个分层节点数组来加载整体树，使用：

``` php
<?php
$repo = $em->getRepository('Entity\Category');
$arrayTree = $repo->childrenHierarchy();
```

All node children are stored under the **__children** key for each node.

### Retrieving as html tree

To load a tree as a **ul - li** html tree use:

所有节点的子节点都存储在**_children**键里。

### 作为html树接收

要作为一个**ul-li** html树来加载一个树，使用:

``` php
<?php
$repo = $em->getRepository('Entity\Category');
$htmlTree = $repo->childrenHierarchy(
    null, /* starting from root nodes */ /* 从根节点开始 */
    false, /* false: load all children, true: only direct */ /* false: 加载全部子孙，true: 只是直接子节点 */
    array(
        'decorate' => true,
        'representationField' => 'slug',
        'html' => true
    )
);
```

### Customize html tree output

### 自定义html树输出

``` php
<?php
$repo = $em->getRepository('Entity\Category');
$options = array(
    'decorate' => true,
    'rootOpen' => '<ul>',
    'rootClose' => '</ul>',
    'childOpen' => '<li>',
    'childClose' => '</li>',
    'nodeDecorator' => function($node) {
        return '<a href="/page/'.$node['slug'].'">'.$node[$field].'</a>';
    }
);
$htmlTree = $repo->childrenHierarchy(
    null, /* starting from root nodes */ /* 从根节点开始 */
    false, /* false: load all children, true: only direct */ /* false: 加载全部子孙，true: 只含直接子节点 */
    $options
);
```

### Generate your own node list

### 生成你自己的节点列表

``` php
<?php
$repo = $em->getRepository('Entity\Category');
$query = $entityManager
    ->createQueryBuilder()
    ->select('node')
    ->from('Entity\Category', 'node')
    ->orderBy('node.root, node.lft', 'ASC')
    ->where('node.root = 1')
    ->getQuery()
;
$options = array('decorate' => true);
$tree = $repo->buildTree($query->getArrayResult(), $options);
```

### Using routes in decorator, show only selected items, return unlimited levels items as 2 levels

### 在装饰器使用路由，只显示选中子项，将无限层级子项作为两层返回

``` php
<?php
$controller = $this;
        $tree = $root->childrenHierarchy(null,false,array('decorate' => true,
            'rootOpen' => function($tree) {
                if(count($tree) && ($tree[0]['lvl'] == 0)){
                        return '<div class="catalog-list">';
                }
            },
            'rootClose' => function($child) {
                if(count($child) && ($child[0]['lvl'] == 0)){
                                return '</div>';
                }
             },
            'childOpen' => '',
            'childClose' => '',
            'nodeDecorator' => function($node) use (&$controller) {
                if($node['lvl'] == 1) {
                    return '<h1>'.$node['title'].'</h1>';
                }elseif($node["isVisibleOnHome"]) {
                    return '<a href="'.$controller->generateUrl("wareTree",array("id"=>$node['id'])).'">'.$node['title'].'</a>&nbsp;';
                }
            }
        ));
```

<a name="advanced-examples"></a>

## Advanced examples:

### Nesting Translatable and Sluggable extensions

If you want to attach **TranslatableListener** and also add it to EventManager after
the **SluggableListener** and **TreeListener**. It is important because slug must be generated first
before the creation of it`s translation.

## 高级示例：

### 嵌套可译性和可slug扩展

如果你想附上**TranslatableListener**，并在**SluggableListener**和**TreeListener**之后将它添加到EventManager。这很重要，因为slug必须在翻译创建前首先生成。

``` php
<?php
$evm = new \Doctrine\Common\EventManager();
$treeListener = new \Gedmo\Tree\TreeListener();
$evm->addEventSubscriber($treeListener);
$sluggableListener = new \Gedmo\Sluggable\SluggableListener();
$evm->addEventSubscriber($sluggableListener);
$translatableListener = new \Gedmo\Translatable\TranslatableListener();
$translatableListener->setTranslatableLocale('en_us');
$evm->addEventSubscriber($translatableListener);
// now this event manager should be passed to entity manager constructor
// 现在这个事件管理器应该传给实体管理器的构造器了
```

And the Entity should look like:

实体应该看上去是这样的：

``` php
<?php
namespace Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @Gedmo\Sluggable
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @Gedmo\Translatable
     * @Gedmo\Slug
     * @ORM\Column(length=128)
     */
    private $slug;

    public function getId()
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Category $parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
```

Yaml mapped Category: **/mapping/yaml/Entity.Category.dcm.yml**

Yaml映射的Category: **/mapping/yaml/Entity.Category.dcm.yml**

```
---
Entity\Category:
  type: entity
  repositoryClass: Gedmo\Tree\Entity\Repository\NestedTreeRepository
  table: categories
  gedmo:
    tree:
      type: nested
  id:
    id:
      type: integer
      generator:
        strategy: AUTO
  fields:
    title:
      type: string
      length: 64
      gedmo:
        - translatable
        - sluggable
    lft:
      type: integer
      gedmo:
        - treeLeft
    rgt:
      type: integer
      gedmo:
        - treeRight
    lvl:
      type: integer
      gedmo:
        - treeLevel
    slug:
      type: string
      length: 128
      gedmo:
        - translatable
        - slug
  manyToOne:
    root:
      targetEntity: Entity\Category
      joinColumn:
        referencedColumnName: id
        onDelete: CASCADE
      gedmo:
        - treeRoot
    parent:
      targetEntity: Entity\Category
      inversedBy: children
      joinColumn:
        referencedColumnName: id
        onDelete: CASCADE
      gedmo:
        - treeParent
  oneToMany:
    children:
      targetEntity: Entity\Category
      mappedBy: parent
```

**Note:** If you use dql without object hydration, the nodes will not be
translated, because the postLoad event never will be triggered

Now the generated treenode slug will be translated by Translatable behavior.

It's as easy as that. Any suggestions on improvements are very welcome.

<a name="materialized-path"></a>

## Materialized Path

### Important notes before defining the schema

- If you use MongoDB you should activate the locking mechanism provided to avoid inconsistencies in cases where concurrent
  modifications on the tree could occur. Look at the MongoDB example of schema definition to see how it must be configured.
- If your **TreePathSource** field is of type "string", then the primary key will be concatenated in the form: "value-id".
   This is to allow you to use non-unique values as the path source. For example, this could be very useful if you need to
   use the date as the path source (maybe to create a tree of comments and order them by date). If you want to change this
   default behaviour you can set the attribute "appendId" of **TreePath** to true or false. By default the path does not start
   with the given separator but ends with it. You can customize this behaviour with "startsWithSeparator" and "endsWithSeparator".
   `@Gedmo\TreePath(appendId=false, startsWithSeparator=true, endsWithSeparator=false)`
- **TreePath** field can only be of types: string, text
- **TreePathSource** field can only be of types: id, integer, smallint, bigint, string, int, float (I include here all the
  variations of the field types, including the ORM and ODM for MongoDB ones).
- **TreeLockTime** must be of type "date" (used only in MongoDB for now).
- **TreePathHash** allows you to define a field that is automatically filled with the md5 hash of the path. This field could be necessary if you want to set a unique constraint on the database table.

### ORM Entity example (Annotations)



**注意:** 如果你使用没有对象混合的纯dql，节点将不被翻译，因为postLoad事件永远也不会被触发。

现在生成的树节点slug将通过翻译功能完成翻译。

就这么简单。欢迎给我提供改进的建议。

## 具体化路径

### 定义schema之前的重要注意事项

- 如果你使用MongoDB，你应该开启提供的锁机制来避免树里可能发生的同时修改的情况造成的前后矛盾。查看MongoDB示例里关于schema定义的例子来看看它必须怎样配置。

- 如果你的**TreePathSource**字段是"string"类型，那么主键会将按照"value-id"的形式关联起来。

  这允许你使用非唯一值作为路径源。例如，如果你需要使用日期作为路径源这就很有用了（或许用来创建一个树状回复，并用日期排序它们）。如果你想要改变这个默认行为，你可以设定**TreePath**的"appendID"属性为true或false。默认路径不以给定的分隔符开始，但结尾用它。你可以自定义这个行为，用"startsWithSeparator"和"endsWithSeparator".

  `@Gedmo\TreePath(appendId=false, startsWithSeparator=true, endsWithSeparator=false)`

- **TreePath** 字段只能是以下类型：string, text

- **TreePathSource** 字段只能是以下类型：id, integer, smallint, bigint, string, int, float(我在这里涵盖了全部的字段类型变体，包括ORM和MongoDB的ODM)。

- **TreeLockTime** 必须是"date"类型（现在只用在MongoDB）。

- **TreePathHash** 允许你定义一个字段，其自动以路径的md5哈希来填写。如果你想在数据库表里设定一个唯一限定，这个字段是必要的。

### ORM Entity example (Annotations)

### ORM实体示例(Annotations)

``` php
<?php

namespace Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\MaterializedPathRepository")
 * @Gedmo\Tree(type="materializedPath")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @Gedmo\TreePath
     * @ORM\Column(length=3000, nullable=true)
     */
    private $path;

    /**
     * @Gedmo\TreePathSource
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getLevel()
    {
        return $this->level;
    }
}
```

### MongoDB example (Annotations)

### MongoDB示例(Annotations)

``` php
<?php

namespace Document;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MONGO;

/**
 * @MONGO\Document(repositoryClass="Gedmo\Tree\Document\MongoDB\Repository\MaterializedPathRepository")
 * @Gedmo\Tree(type="materializedPath", activateLocking=true)
 */
class Category
{
    /**
     * @MONGO\Id
     */
    private $id;

    /**
     * @MONGO\Field(type="string")
     * @Gedmo\TreePathSource
     */
    private $title;

    /**
     * @MONGO\Field(type="string")
     * @Gedmo\TreePath(separator="|")
     */
    private $path;

    /**
     * @Gedmo\TreeParent
     * @MONGO\ReferenceOne(targetDocument="Category")
     */
    private $parent;

    /**
     * @Gedmo\TreeLevel
     * @MONGO\Field(type="int")
     */
    private $level;

    /**
     * @Gedmo\TreeLockTime
     * @MONGO\Field(type="date")
     */
    private $lockTime;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getLockTime()
    {
        return $this->lockTime;
    }
}
```

### MongoDB example (Yaml)

### MongoDB示例(Yaml)

```
YourNamespace\Document\Category:
    type:               mappedSuperclass
    repositoryClass:    Gedmo\Tree\Document\MongoDB\Repository\MaterializedPathRepository
    collection:         categories
    gedmo:
        tree:
            type: materializedPath
            activateLocking: true
    fields:
        id:
            id:     true
        title:
            type:   string
            gedmo:
                -   sluggable
        slug:
            type:   string
            gedmo:
                0:  treePathSource
                slug:
                    unique:     false
                    fields:
                        - title
        path:
            type:   string
            gedmo:
                treePath:
                    separator:           '/'
                    appendId:            false
                    startsWithSeparator: false  # default
                    endsWithSeparator:   true   # default
        level:
            type:   int
            nullable:   true
            gedmo:
                -   treeLevel
        lockTime:
            type:   date
            gedmo:
                -   treeLockTime
        hash:
            type:   string
            gedmo:
                -   treePathHash
        parent:
            reference:  true
            type:       one
            inversedBy: children
            targetDocument: YourNamespace\Document\Category
            simple:     true
            gedmo:
                -   treeParent
```

### Path generation

When an entity is inserted, a path is generated using the value of the field configured as the TreePathSource.
For example:

### Path 生成

当一个实体被插入，一个路径就生成了，其用配置了TreePathSource的字段的值来生成。

例如：

``` php
$food = new Category();
$food->setTitle('Food');

$em->persist($food);
$em->flush();

// This would print "Food-1" assuming the id is 1.
// 这将打印"Food-1"，假设id是1。
echo $food->getPath();

$fruits = new Category();
$fruits->setTitle('Fruits');
$fruits->setParent($food);

$em->persist($fruits);
$em->flush();

// This would print "Food-1,Fruits-2" assuming that $food id is 1,
// $fruits id is 2 and separator = "," (the default value)
// 这将打印"Food-1,Fruits-2"，假设$food的id是1, $fruits id是2，分隔符是","(默认值)
echo $fruits->getPath();
```

### Locking mechanism for MongoDB

Why do we need a locking mechanism for MongoDB? Sadly, MongoDB lacks full transactional support, so if two or more
users try to modify the same tree concurrently, it could lead to an inconsistent tree. So we've implemented a simple
locking mechanism to avoid this type of problems. It works like this: As soon as a user tries to modify a node of a tree,
it first check if the root node is locked (or if the current lock has expired).

If it is locked, then it throws an exception of type "Gedmo\Exception\TreeLockingException". If it's not locked,
it locks the tree and proceeds with the modification. After all the modifications are done, the lock is freed.

If, for some reason, the lock couldn't get freed, there's a lock timeout configured with a default time of 3 seconds.
You can change this value using the **lockingTimeout** parameter under the Tree annotation (or equivalent in XML and YML).
You must pass a value in seconds to this parameter.

<a name="closure-table"></a>

### MongoDB的锁定机制

为什么我们在MongoDB需要一个锁定机制？很不幸，MongoDB缺乏事务支持，所以如果两个或更多用户同时尝试修改同一个树，它可能会导致一个反常的树。所以我们得完成一个简单的锁定机制来避免这类问题。它像这样工作：就在一个用户尝试修改一个树的节点时，它首先检查根节点是否被锁定（或者是否当前锁定过期了）。如果锁定了，它会抛出一个"Gedmo\Exception\TreeLockingException".如果没锁定，它会锁定树并继续这个修改过程。在所有修改完成后，这个锁定会重获自由。出于一些原因，如果这锁定不能释放，会有一个默认为3秒的锁定失效时间。你可以在Tree annotation下用**lockingTimeout**参数来修改这个值。(或者等价的在XML和YML里)。你得传一个以秒为单位的值给这个参数。

## Closure Table

To be able to use this strategy, you'll need an additional entity which represents the closures. We already provide you an abstract
entity, so you only need to extend it.

### Closure Entity

## 闭包表

为了能够使用这个机制，你将需要一个额外的实体，其代表了闭包。我们已经给你提供了一个抽象实体，所以你只需要扩展它即可。

### 闭包实体

``` php
<?php

namespace YourNamespace\Entity;

use Gedmo\Tree\Entity\MappedSuperclass\AbstractClosure;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CategoryClosure extends AbstractClosure
{
}
```

Next step, define your entity.

### ORM Entity example (Annotations)

下一步，定义你的实体

### ORM实体示例(Annotation)

``` php
<?php

namespace YourNamespace\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="closure")
 * @Gedmo\TreeClosure(class="YourNamespace\Entity\CategoryClosure")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\ClosureTreeRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * This parameter is optional for the closure strategy
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeLevel
     */
    private $level;

    /**
     * @Gedmo\TreeParent
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    private $parent;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addClosure(CategoryClosure $closure)
    {
        $this->closures[] = $closure;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }
}
```

And that's it!

就这些！


<a name="repository-methods"></a>

## Repository Methods (All strategies)

There are repository methods that are available for you in all the strategies:

* **getRootNodes** / **getRootNodesQuery** / **getRootNodesQueryBuilder**: Returns an array with the available root nodes. Arguments:
  - *sortByField*: An optional field to order the root nodes. Defaults to "null".
  - *direction*: In case the first argument is used, you can pass the direction here: "asc" or "desc". Defaults to "asc".
* **getChildren** / **getChildrenQuery** / **getChildrenQueryBuilder**: Returns an array of children nodes. Arguments:
  - *node*: If you pass a node, the method will return its children. Defaults to "null" (this means it will return ALL nodes).
  - *direct*: If you pass true as a value for this argument, you'll get only the direct children of the node
    (or only the root nodes if you pass "null" to the "node" argument).
  - *sortByField*: An optional field to sort the children. Defaults to "null".
  - *direction*: If you use the "sortByField" argument, this allows you to set the direction: "asc" or "desc". Defaults to "asc".
  - *includeNode*: Using "true", this argument allows you to include in the result the node you passed as the first argument. Defaults to "false".
* **childrenHierarchy**: This useful method allows you to build an array of nodes representing the hierarchy of a tree. Arguments:
  - *node*: If you pass a node, the method will return its children. Defaults to "null" (this means it will return ALL nodes).
  - *direct*: If you pass true as a value for this argument, you'll get only the direct children of the node
  - *options*: An array of options that allows you to decorate the results with HTML. Available options:
      * decorate: boolean (false) - retrieves tree as UL->LI tree
      * nodeDecorator: Closure (null) - uses $node as argument and returns decorated item as string
      * rootOpen: string || Closure ('\<ul\>') - branch start, closure will be given $children as a parameter
      * rootClose: string ('\</ul\>') - branch close
      * childStart: string || Closure ('\<li\>') - start of node, closure will be given $node as a parameter
      * childClose: string ('\</li\>') - close of node
      * childSort: array || keys allowed: field: field to sort on, dir: direction. 'asc' or 'desc'
  - *includeNode*: Using "true", this argument allows you to include in the result the node you passed as the first argument. Defaults to "false".
* **setChildrenIndex** / **getChildrenIndex**: These methods allow you to change the default index used to hold the children when you use the **childrenHierarchy** method. Index defaults to "__children".

This list is not complete yet. We're working on including more methods in the common API offered by repositories of all the strategies.
Soon we'll be adding more helpful methods here.

## 仓库方法 (全部策略)

这里是可得的全部策略的的仓库方法：

- **getRootNodes** / **getRootNodesQuery** / **getRootNodesQueryBuilder**: 返回可得根节点的数组。参数：
  - sortByField: 一个用来排序根节点的选项字段。默认为"null".
  - direction: 如果用了第一个参数，你可以在这里规定方向："asc"或"desc"。默认为"asc"。
- **getChildren** / **getChildrenQuery** / **getChildrenQueryBuilder**: 返回一个子节点数组。参数：
  - node: 如果你传入一个节点，此方法会返回它的子节点。默认为"null"（这意思就是它将返回全部节点）。
  - direct: 如果你传入true作为这个参数值，你只会得到此节点直接的子节点(或者如果你传入"null"到"node"参数就只有根节点了)。
  - sortByField：一个选项字段，用来排序子节点。默认为"null"。
  - direction: 如果你使用""sortByField"参数，这个允许你设定方向："asc"或"desc"。默认为"asc"。
  - includeNode: 使用"true", 这个参数允许你在结果里包含你传递给第一个参数的节点。默认为"false"。
- **childrenHierarchy**: 这个实用的方法允许你创建一个代表树层级的节点数组。参数：
  - node: 如果你传入一个节点，此方法将返回它的子节点。默认为"null"(这意味着它将返回全部节点)。
  - direct: 如果你传入true作为这个参数的值，你只会获得此节点的直接子节点。
  - options: 一个选项数组，允许你以HTML装饰结果。可得选项：
    - decorate: boolean(false)，以UL->LI树形式获取树
    - nodeDecorator: Closure(null)，使用$node作为参数，并返回装饰后子项字符串。
    - rootOpen: string || Closure('\<ul\>')，分支起点，会给闭包一个$node作为参数。
    - rootClose: string('\</ul>')，分支结束符号。
    - childStart: string || Closure('\<li\>')，节点起点，会给闭包一个$node作为参数。
    - childClose: string('\</li\>')，节点结束符号。
    - childSort: array || 允许的key: 字段: 用来排序的字段，dir: 方向。'asc'或'desc'
  - includeNode: 设为'true'，这个参数允许你在结果里包含你传入首个参数的节点。默认为'false'。
- **setChildrenIndex** / **getChildrenIndex**: 当你使用了**childrenHierarchy**方法时，这些方法允许你修改用来持有子节点的默认索引。所以默认为"_children"。

这个列表还未完成。我们正在添加进来更多的由所有策略仓库提供的一般API的方法。很快我们会添加更多有用的方法到这里。



## Repository Methods (Closure Trees only)

It is possible to obtain all ancestors from a particular node in a efficient way when using the Closure Tree strategy. These are the repository methods:

* **getAncestors** / **getAncestorsQuery** / **getAncestorsQueryBuilder**: Returns an array of ancestors nodes. Arguments:
  - *node*: If you pass a node, the method will return its ancestors. Defaults to "null" (this means it will return ALL nodes).
  - *direct*: If you pass true as a value for this argument, you'll get only the direct ancestors of the node
  - *sortByField*: An optional field to sort the children. Defaults to "null".
  - *direction*: If you use the "sortByField" argument, this allows you to set the direction: "asc" or "desc". Defaults to "asc".
  - *includeNode*: Using "true", this argument allows you to include in the result the node you passed as the first argument. Defaults to "false".

## 仓库方法(只针对闭包树)

当使用闭包树策略时可以用一个不同的方式来获取特定节点的全部祖先节点：

- **getAncestors** / **getAncestorsQuery** / **getAncestorsQueryBuilder**: 返回一个祖先节点数组。参数：
  - node: 如果你传入一个节点，此方法将返回它的祖先。默认为"null"(这意味着它会返回全部节点)。
  - direct: 如果你传入true作为这个参数值，你将只得到此节点的直接祖先节点。
  - sortByField: 一个选项字段，用来排序祖先节点。默认为"null"。
  - direction: 如果你使用了"sortByField"参数，这个允许你设定方向："asc"或"desc"。默认为"asc"。
  - includeNode: 使用"true"，这个参数允许你在结果里包含你传入首个参数的节点。默认为"false"。