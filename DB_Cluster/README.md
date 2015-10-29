#基于PhalApi的DB集群拓展 V0.1bate


引入集群拓展拓展库
DI()->loader->addDirs('Library/DB_Cluster');
初始化配置文件
DI()->Cluster_DB = new Cluster_Access(DI()->config->get('cluster'));


    /**
     * 可以在PhalApi_DI类上面增加下面注释(增加提示功能)
     * ------------------------------------------------------
     * @property Cluster_Access           $cluster      配置
     * ------------------------------------------------------
     */