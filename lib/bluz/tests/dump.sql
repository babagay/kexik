/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `test` */

CREATE TABLE `test` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(512) DEFAULT NULL,
  `status` enum('active','disable','delete') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;

/*Data for the table `test` */

insert  into `test`(`id`,`name`,`email`,`status`) values (4,'Patric','nunc.sed@velitegestas.com','active'),(5,'Tallula','euismod@justo.org','active'),(7,'Courtney','lacus.Nulla@nonhendreritid.org','active'),(8,'April','tincidunt.nibh.Phasellus@adipiscingMaurismolestie.ca','disable'),(10,'Lesley','non@milaciniamattis.com','disable'),(12,'Frances','libero.mauris.aliquam@faucibusid.ca','disable'),(13,'Emma Nilson','magna@temporest.edu','disable'),(14,'Emerald','nunc.nulla@Lorem.ca','disable'),(15,'Marsden','Donec.dignissim.magna@posuereatvelit.org','delete'),(16,'Jonah','dictum@pharetra.ca','disable'),(17,'Connor','congue.In.scelerisque@Integervulputaterisus.ca','disable'),(18,'Jessica','imperdiet.ornare@iaculisnec.com','delete'),(19,'Derek','sollicitudin@morbitristique.edu','disable'),(20,'Daniel','dui@at.com','disable'),(21,'Azalia Two','at@enimconsequatpurus.ca','delete'),(22,'Karina','eu.eros@nonummy.org','disable'),(23,'Samuel','tellus@Seddiamlorem.org','delete'),(24,'Urielle','mattis.Integer@Donec.com','active'),(25,'Jamal','adipiscing.elit.Etiam@consectetueradipiscing.ca','disable'),(26,'Garrison','urna.Nullam@Quisque.org','delete'),(27,'Skyler','placerat.Cras.dictum@tempor.org','disable'),(28,'Alexa','Nullam.enim@lacusvariuset.edu','delete'),(29,'Zena','nec.leo@nislarcuiaculis.com','disable'),(30,'Mary','sit.amet@vehicularisusNulla.ca','active'),(31,'Raven','Donec@tellus.ca','active'),(32,'Leigh','sem@nonfeugiat.ca','disable'),(33,'Ginger','Integer.mollis.Integer@vitaeorci.edu','delete'),(34,'Leonard','neque@malesuadafames.ca','active'),(35,'Abdul','aliquam.arcu@tinciduntorci.org','disable'),(36,'Robin','lacus.Etiam.bibendum@lectus.com','delete'),(37,'Elaine','dis.parturient@Aeneansed.ca','disable'),(38,'Allistair','amet.metus@Mauris.com','disable'),(39,'Alika','Lorem@velquam.com','active'),(40,'Wylie','dis.parturient@dolornonummy.edu','disable'),(41,'Lareina','et.rutrum@mi.org','delete'),(42,'Aurelia','augue.porttitor@vitaevelit.com','active'),(43,'Ivor','vitae.semper.egestas@egestas.edu','disable'),(44,'Mikayla','Nunc.ullamcorper@orcisem.com','active'),(45,'Nola','eget.lacus@tristique.org','delete'),(46,'Angela','Etiam.imperdiet.dictum@rhoncusProinnisl.com','active'),(47,'Dante','egestas.Aliquam.fringilla@Curabiturdictum.org','active'),(48,'Sybill','mauris@sodales.com','disable'),(49,'Quentin','molestie.in@felisNullatempor.org','disable'),(50,'Hyacinth','egestas.a@vestibulumnec.org','delete'),(51,'Lev','id@laciniaSedcongue.ca','active'),(52,'Aquila','ac@accumsanconvallis.edu','disable'),(53,'Morgan','facilisis.vitae.orci@felispurusac.edu','delete'),(54,'Libby','porttitor@Etiamligula.ca','disable'),(55,'Brian','vitae.aliquam@sollicitudinadipiscingligula.ca','delete'),(56,'Uriel','ipsum.nunc@ametnulla.org','delete'),(57,'Keaton','at.sem.molestie@in.com','disable'),(58,'Isaac','Nulla.facilisis@in.org','active'),(59,'Aline','Mauris.vestibulum.neque@ultriciesligula.ca','disable'),(60,'Garth','Pellentesque.habitant@idrisusquis.com','delete'),(61,'Brielle','risus.at@atortorNunc.ca','disable'),(62,'Devin','lacinia.vitae.sodales@ametultriciessem.ca','delete'),(63,'Michelle','vitae.orci.Phasellus@Duis.ca','active'),(64,'Iola','nunc.id@auctorvitaealiquet.edu','delete'),(65,'Kirk','dictum@eu.edu','delete'),(66,'Amethyst','libero.dui.nec@parturientmontesnascetur.com','delete'),(67,'Wallace','eu.nibh@faucibusMorbi.edu','active'),(68,'Octavia','neque.vitae@nonmagnaNam.ca','disable'),(69,'Carolyn','eleifend.vitae.erat@Donec.org','active'),(70,'Jade','Nullam.enim@Nulla.ca','active'),(71,'Isabella','nostra.per.inceptos@gravidasagittisDuis.ca','active'),(72,'Clio','porttitor.tellus.non@Utsemperpretium.com','active'),(73,'Basia','odio@eratEtiamvestibulum.org','disable'),(74,'Ava','rutrum@eleifendnecmalesuada.edu','active'),(75,'Winifred','Nullam.velit.dui@est.ca','active'),(76,'Lila','dictum.magna@commodo.org','disable'),(77,'Rebekah','Morbi@aliquamiaculislacus.org','delete'),(78,'Ria','semper.tellus.id@nuncsitamet.com','active'),(79,'Briar','non.lobortis.quis@Donecatarcu.ca','delete'),(80,'Kasper','libero@idsapien.ca','delete'),(81,'Astra','Duis.sit@Aliquamgravidamauris.org','active'),(82,'Heather','sagittis.Nullam@erat.com','disable'),(83,'Fletcher','ipsum@elit.com','active'),(84,'Hyatt','mi@at.com','active'),(85,'Shelley','mi.fringilla.mi@mollisDuissit.org','active'),(86,'Cullen','varius.et@turpis.ca','disable'),(87,'Alexis','Donec.nibh.Quisque@nonummy.com','active'),(88,'Jane','nec.quam@sed.edu','delete'),(89,'Silas','lacinia.at.iaculis@orci.edu','active'),(90,'Leigh','Suspendisse.dui@tellus.edu','active'),(91,'Alfreda','magnis.dis@ipsum.edu','disable'),(92,'Michelle','dis.parturient@magnisdis.ca','active'),(93,'Rahim','dolor@nuncsed.com','delete'),(94,'Yeo','Sed@Sedid.org','disable'),(95,'Adena','dis.parturient.montes@acurnaUt.com','delete'),(96,'Tobias','ac.libero.nec@congueelit.ca','delete'),(97,'Sybil','at.libero.Morbi@amet.edu','disable'),(98,'Dale','Donec@etcommodo.ca','active'),(99,'Penelope','egestas@quamCurabiturvel.edu','active'),(100,'Amena','Phasellus.at.augue@justoProinnon.com','disable'),(117,'Jr. Smith','Smith@Test.com','disable');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
